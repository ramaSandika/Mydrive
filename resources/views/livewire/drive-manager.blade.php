<div>
    <!-- Modal Overlay untuk Pindah Individual, Ganti Nama, & Pindah Massal -->
    @if($renameId || $moveId || $isBulkMove)
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 50;">
            <div style="background: white; padding: 2rem; border-radius: 12px; width: 400px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                
                @if($renameId)
                    <h3 style="margin-top: 0; color: #1e293b;">✏️ Ganti Nama {{ ucfirst($renameType) }}</h3>
                    <input type="text" wire:model="newName" style="width: 90%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; margin-bottom: 1rem; outline: none;">
                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button wire:click="cancelAction" style="padding: 10px 16px; border: none; background: #f1f5f9; border-radius: 6px; cursor: pointer; font-weight: 600;">Batal</button>
                        <button wire:click="saveRename" style="padding: 10px 16px; border: none; background: #2563eb; color: white; border-radius: 6px; cursor: pointer; font-weight: 600;">Simpan</button>
                    </div>
                @endif

                @if($moveId)
                    <h3 style="margin-top: 0; color: #1e293b;">➡️ Pindahkan {{ ucfirst($moveType) }}</h3>
                    <p style="font-size: 0.9rem; color: #64748b;">Pilih lokasi tujuan:</p>
                    <select wire:model="targetFolderId" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; margin-bottom: 1.5rem; outline: none;">
                        <option value="">🗄 Direktori Utama (Root)</option>
                        @foreach($allAvailableFolders as $availableFolder)
                            <option value="{{ $availableFolder->id }}">📁 {{ $availableFolder->name }}</option>
                        @endforeach
                    </select>
                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button wire:click="cancelAction" style="padding: 10px 16px; border: none; background: #f1f5f9; border-radius: 6px; cursor: pointer; font-weight: 600;">Batal</button>
                        <button wire:click="saveMove" style="padding: 10px 16px; border: none; background: #2563eb; color: white; border-radius: 6px; cursor: pointer; font-weight: 600;">Pindahkan</button>
                    </div>
                @endif

                <!-- MODAL BARU: BULK MOVE -->
                @if($isBulkMove)
                    <h3 style="margin-top: 0; color: #1e293b;">📦 Pindahkan Item Terpilih sekaligus</h3>
                    <p style="font-size: 0.9rem; color: #64748b;">Pilih lokasi tujuan baru untuk semua item yang dicentang:</p>
                    <select wire:model="targetFolderId" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; margin-bottom: 1.5rem; outline: none;">
                        <option value="">🗄 Direktori Utama (Root)</option>
                        @foreach($allAvailableFolders as $availableFolder)
                            <option value="{{ $availableFolder->id }}">📁 {{ $availableFolder->name }}</option>
                        @endforeach
                    </select>
                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button wire:click="cancelAction" style="padding: 10px 16px; border: none; background: #f1f5f9; border-radius: 6px; cursor: pointer; font-weight: 600;">Batal</button>
                        <button wire:click="saveBulkMove" style="padding: 10px 16px; border: none; background: #10b981; color: white; border-radius: 6px; cursor: pointer; font-weight: 600;">Pindahkan Semua</button>
                    </div>
                @endif

            </div>
        </div>
    @endif

    <!-- Header Hero (Diubah Menjadi MyDrive) -->
    <div style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; padding: 3rem 2rem; border-radius: 16px; margin-bottom: 2rem; text-align: center; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);">
        <h1 style="margin: 0 0 10px 0; font-size: 2.2rem;">MyDrive Cloud Drive</h1>
        <p style="margin: 0; opacity: 0.9; font-size: 1.1rem;">Platform Penyimpanan Terdesentralisasi & Aman.</p>
    </div>

    <!-- Area Aksi (Upload & Buat Folder) -->
    <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap;">
        
        <!-- Panel Upload File -->
        <div style="flex: 1; background: white; padding: 2rem; border-radius: 16px; border: 2px dashed #cbd5e1; text-align: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false; progress = 0" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                
                <h3 style="margin-top: 0; color: #1e293b;">☁️ Upload File Baru</h3>
                
                <input type="file" wire:model.live="uploadedFiles" style="display:none" id="fileInput" multiple>
                <button onclick="document.getElementById('fileInput').click()" style="background: #eff6ff; color: #2563eb; padding: 12px 28px; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 1rem;">Jelajahi File</button>

                <!-- Perbaikan Struktur Progress Bar (Menggunakan Opsi Object Data Binding) -->
                <div x-show="isUploading" style="margin-top: 20px; display: none;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 8px; font-weight: bold; color: #2563eb;">
                        <span>Membaca file ke sistem...</span>
                        <span x-text="progress + '%'"></span>
                    </div>
                    <div style="width: 100%; background-color: #e2e8f0; border-radius: 8px; overflow: hidden; height: 12px; border: 1px solid #cbd5e1;">
                        <div style="height: 100%; background-color: #2563eb; transition: width 0.1s linear;" :style="{ width: progress + '%' }"></div>
                    </div>
                </div>

                @if($uploadedFiles && count($uploadedFiles) > 0)
                    <div style="margin-top: 20px; background: #f8fafc; padding: 15px; border-radius: 8px; text-align: left; border: 1px solid #e2e8f0;">
                        <p style="font-weight: 700; font-size: 0.9rem; margin-top: 0; color: #334155;">File Siap Diunggah:</p>
                        <ul style="font-size: 0.85rem; margin: 5px 0; color: #475569; max-height: 100px; overflow-y: auto; padding-left: 20px;">
                            @foreach($uploadedFiles as $tempFile)
                                <li style="margin-bottom: 6px;">📄 {{ $tempFile->getClientOriginalName() }}</li>
                            @endforeach
                        </ul>
                        <button wire:click="saveUploads" style="background: #10b981; color: white; width: 100%; margin-top: 15px; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                            <span wire:loading.remove wire:target="saveUploads">⬆️ Mulai Upload ke MinIO</span>
                            <span wire:loading wire:target="saveUploads">⏳ Menyimpan ke Storage...</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Panel Buat Folder -->
        <div style="flex: 1; background: white; padding: 2rem; border-radius: 16px; border: 1px solid #e2e8f0; text-align: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <h3 style="margin-top: 0; color: #1e293b;">📁 Buat Folder Baru</h3>
            <p style="color: #64748b; font-size: 0.95rem;">Organisir struktur layout file Anda.</p>
            <input type="text" wire:model="newFolderName" placeholder="Ketik nama folder..." style="padding: 14px; width: 85%; border: 2px solid #e2e8f0; border-radius: 8px; margin-top: 10px; outline: none;">
            <button wire:click="createFolder" style="margin-top: 15px; display: block; margin-inline: auto; background: #1e293b; color: white; padding: 12px 28px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Tambah Folder</button>
        </div>
    </div>

    <!-- Multi-Select Action Bar dengan Tambahan Fitur Pindah Massal -->
    @if(count($selectedFiles) > 0 || count($selectedFolders) > 0)
        <div style="background: #fef2f2; padding: 1rem 1.5rem; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border: 1px solid #fecaca;">
            <span style="color: #991b1b; font-weight: 700; font-size: 1.1rem;">
                ✅ Terpilih: {{ count($selectedFolders) }} Folder, {{ count($selectedFiles) }} File
            </span>
            <div>
                <!-- Tombol Aksi Massal Baru -->
                <button wire:click="startBulkMove" style="background: #2563eb; color: white; padding: 10px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-right: 10px;">📦 Pindahkan Terpilih</button>
                <button wire:click="deleteSelected" style="background: #ef4444; color: white; padding: 10px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">🗑 Hapus Item</button>
            </div>
        </div>
    @endif

    <!-- Tabel Direktori -->
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px;">
            <h3 style="margin: 0; display: flex; align-items: center; gap: 12px; font-size: 1.3rem; color: #1e293b;">
                @if(!empty($search))
                    🔍 Hasil untuk: "{{ $search }}"
                @elseif($currentFolderId)
                    <button wire:click="goUp" style="background: white; border: 2px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; cursor: pointer; color: #0f172a; font-weight: bold;">
                        ⬅ Kembali
                    </button>
                    📂 {{ $currentFolder->name }}
                @else
                    🗄 Direktori Utama
                @endif
            </h3>
            <div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="🔍 Cari sesuatu..." style="padding: 12px 16px; width: 280px; border: 2px solid #e2e8f0; border-radius: 8px; outline: none; background: #f8fafc;">
            </div>
        </div>
        
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr>
                    <th style="padding: 16px 12px; text-align: center; border-bottom: 2px solid #e2e8f0; color: #64748b; width: 60px;">Pilih</th>
                    <th style="padding: 16px 12px; text-align: left; border-bottom: 2px solid #e2e8f0; color: #64748b;">Nama Item</th>
                    <th style="padding: 16px 12px; text-align: left; border-bottom: 2px solid #e2e8f0; color: #64748b;">Ukuran</th>
                    <th style="padding: 16px 12px; text-align: left; border-bottom: 2px solid #e2e8f0; color: #64748b;">Aksi Item</th>
                </tr>
            </thead>
            <tbody>
                <!-- Daftar Folder -->
                @foreach($folders as $folder)
                    <tr style="transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 16px 12px; text-align: center; border-bottom: 1px solid #f1f5f9;">
                            <input type="checkbox" value="{{ $folder->id }}" wire:model.live="selectedFolders" style="transform: scale(1.3); accent-color: #2563eb;">
                        </td>
                        <td style="padding: 16px 12px; border-bottom: 1px solid #f1f5f9;">
                            <button wire:click="openFolder({{ $folder->id }})" style="background: none; border: none; cursor: pointer; color: #1e293b; font-weight: 700; display: flex; align-items: center; gap: 10px; font-size: 1.05rem; padding: 0;">
                                📁 {{ $folder->name }}
                            </button>
                        </td>
                        <td style="padding: 16px 12px; color: #94a3b8; border-bottom: 1px solid #f1f5f9;">-</td>
                        <td style="padding: 16px 12px; border-bottom: 1px solid #f1f5f9;">
                            <button wire:click="startRename({{ $folder->id }}, 'folder', '{{ $folder->name }}')" style="background: #f1f5f9; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-right: 5px; font-weight: bold; color: #475569;">✏️ Edit</button>
                            <button wire:click="startMove({{ $folder->id }}, 'folder')" style="background: #f1f5f9; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: bold; color: #475569;">➡️ Pindah</button>
                        </td>
                    </tr>
                @endforeach

                <!-- Daftar File -->
                @foreach($files as $file)
                    <tr style="transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 16px 12px; text-align: center; border-bottom: 1px solid #f1f5f9;">
                            <input type="checkbox" value="{{ $file->id }}" wire:model.live="selectedFiles" style="transform: scale(1.3); accent-color: #2563eb;">
                        </td>
                        <td style="padding: 16px 12px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; font-weight: 500; color: #334155;">
                            📄 {{ $file->name }}
                        </td>
                        <td style="padding: 16px 12px; border-bottom: 1px solid #f1f5f9; color: #64748b;">{{ number_format($file->size / 1024, 2) }} KB</td>
                        <td style="padding: 16px 12px; border-bottom: 1px solid #f1f5f9;">
                            <button wire:click="downloadFile({{ $file->id }})" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: bold; margin-right: 5px;">⬇ Unduh</button>
                            <button wire:click="startRename({{ $file->id }}, 'file', '{{ $file->name }}')" style="background: #f1f5f9; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-right: 5px; font-weight: bold; color: #475569;">✏️ Edit</button>
                            <button wire:click="startMove({{ $file->id }}, 'file')" style="background: #f1f5f9; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: bold; color: #475569;">➡️ Pindah</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>