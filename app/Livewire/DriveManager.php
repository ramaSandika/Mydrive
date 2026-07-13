<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Folder;
use App\Models\File;

class DriveManager extends Component
{
    use WithFileUploads;

    public $currentFolderId = null;
    public $currentFolder = null;
    public $search = '';
    
    public $folders = [];
    public $files = [];
    
    public $newFolderName = '';
    public $uploadedFiles = [];
    
    public $selectedFiles = [];
    public $selectedFolders = [];

    // State untuk Single Update
    public $renameId = null;
    public $renameType = ''; 
    public $newName = '';
    public $moveId = null;
    public $moveType = '';

    // State untuk Multi-Update (Bulk Move)
    public $isBulkMove = false;
    
    public $targetFolderId = ''; 
    public $allAvailableFolders = [];

    public function mount()
    {
        $this->loadDirectory();
    }

    public function updatedSearch()
    {
        $this->loadDirectory();
    }

    public function loadDirectory()
    {
        if (!empty($this->search)) {
            $this->folders = Folder::where('name', 'like', '%' . $this->search . '%')->get();
            $this->files = File::where('name', 'like', '%' . $this->search . '%')->get();
            $this->currentFolder = null;
        } else {
            $this->folders = Folder::where('parent_id', $this->currentFolderId)->get();
            $this->files = File::where('folder_id', $this->currentFolderId)->get();
            $this->currentFolder = $this->currentFolderId ? Folder::find($this->currentFolderId) : null;
        }
        
        $this->selectedFiles = [];
        $this->selectedFolders = [];
    }

    public function openFolder($folderId)
    {
        $this->currentFolderId = $folderId;
        $this->search = '';
        $this->loadDirectory();
    }

    public function goUp()
    {
        if ($this->currentFolder) {
            $this->currentFolderId = $this->currentFolder->parent_id;
            $this->loadDirectory();
        }
    }

    public function createFolder()
    {
        $this->validate(['newFolderName' => 'required|string|max:255']);
        Folder::create([
            'name' => $this->newFolderName,
            'parent_id' => $this->currentFolderId
        ]);
        $this->newFolderName = '';
        $this->loadDirectory();
    }

    public function saveUploads()
    {
        // Validasi ditingkatkan menjadi 500 MB (512000 KB)
        $this->validate(['uploadedFiles.*' => 'required|file|max:512000']);
        foreach ($this->uploadedFiles as $file) {
            $path = $file->store('uploads', 'minio');
            File::create([
                'name' => $file->getClientOriginalName(),
                'folder_id' => $this->currentFolderId,
                'minio_path' => $path,
                'size' => $file->getSize()
            ]);
        }
        $this->uploadedFiles = [];
        $this->loadDirectory();
    }

    public function downloadFile($fileId)
    {
        $file = File::find($fileId);
        if ($file && Storage::disk('minio')->exists($file->minio_path)) {
            return response()->streamDownload(function () use ($file) {
                echo Storage::disk('minio')->get($file->minio_path);
            }, $file->name);
        }
    }

    public function startRename($id, $type, $currentName)
    {
        $this->renameId = $id;
        $this->renameType = $type;
        $this->newName = $currentName;
    }

    public function saveRename()
    {
        $this->validate(['newName' => 'required|string|max:255']);
        if ($this->renameType === 'folder') {
            Folder::where('id', $this->renameId)->update(['name' => $this->newName]);
        } else {
            File::where('id', $this->renameId)->update(['name' => $this->newName]);
        }
        $this->renameId = null;
        $this->loadDirectory();
    }

    public function startMove($id, $type)
    {
        $this->moveId = $id;
        $this->moveType = $type;
        $this->targetFolderId = ''; 
        
        if ($type === 'folder') {
            $this->allAvailableFolders = Folder::where('id', '!=', $id)->get();
        } else {
            $this->allAvailableFolders = Folder::all();
        }
    }

    public function saveMove()
    {
        $targetId = $this->targetFolderId === '' ? null : $this->targetFolderId;

        if ($this->moveType === 'folder') {
            Folder::where('id', $this->moveId)->update(['parent_id' => $targetId]);
        } else {
            File::where('id', $this->moveId)->update(['folder_id' => $targetId]);
        }
        $this->moveId = null;
        $this->loadDirectory();
    }

    // --- FITUR BARU: BULK MOVE (MEMINDAHKAN BANYAK ITEM) ---
    public function startBulkMove()
    {
        if (empty($this->selectedFiles) && empty($this->selectedFolders)) {
            return;
        }
        $this->isBulkMove = true;
        $this->targetFolderId = '';
        
        // Ambil semua folder pilihan (Kecuali folder-folder yang sedang dicentang agar tidak looping)
        $this->allAvailableFolders = Folder::whereNotIn('id', $this->selectedFolders)->get();
    }

    public function saveBulkMove()
    {
        $targetId = $this->targetFolderId === '' ? null : $this->targetFolderId;

        if (!empty($this->selectedFolders)) {
            Folder::whereIn('id', $this->selectedFolders)->update(['parent_id' => $targetId]);
        }

        if (!empty($this->selectedFiles)) {
            File::whereIn('id', $this->selectedFiles)->update(['folder_id' => $targetId]);
        }

        $this->isBulkMove = false;
        $this->loadDirectory();
    }

    public function cancelAction()
    {
        $this->renameId = null;
        $this->moveId = null;
        $this->isBulkMove = false;
    }

    public function deleteSelected()
    {
        if (!empty($this->selectedFiles)) {
            $filesToDelete = File::whereIn('id', $this->selectedFiles)->get();
            foreach ($filesToDelete as $file) {
                Storage::disk('minio')->delete($file->minio_path);
                $file->delete();
            }
        }
        if (!empty($this->selectedFolders)) {
            $this->deleteFoldersRecursively($this->selectedFolders);
        }
        $this->loadDirectory();
    }
    
    private function deleteFoldersRecursively($folderIds)
    {
        $folders = Folder::whereIn('id', $folderIds)->with('files', 'subfolders')->get();
        foreach ($folders as $folder) {
            foreach ($folder->files as $file) {
                Storage::disk('minio')->delete($file->minio_path);
            }
            if ($folder->subfolders->count() > 0) {
                $this->deleteFoldersRecursively($folder->subfolders->pluck('id')->toArray());
            }
            $folder->delete();
        }
    }

    public function render()
    {
        return view('livewire.drive-manager');
    }
}