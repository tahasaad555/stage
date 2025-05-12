<div>
    <div class="card">
        <div class="card-title">System Settings</div>
        
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        <form wire:submit.prevent="saveSettings">
            <div class="settings-grid">
                <div class="settings-section">
                    <h3>General Settings</h3>
                    
                    <div class="form-group">
                        <label for="siteName">Site Name</label>
                        <input type="text" id="siteName" wire:model="siteName">
                        @error('siteName') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="siteEmail">Site Email</label>
                        <input type="email" id="siteEmail" wire:model="siteEmail">
                        @error('siteEmail') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="contactPhone">Contact Phone</label>
                        <input type="text" id="contactPhone" wire:model="contactPhone">
                        @error('contactPhone') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" wire:model="address" rows="3"></textarea>
                        @error('address') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="settings-section">
                    <h3>Business Settings</h3>
                    
                    <div class="form-group">
                        <label for="commission">Commission Rate (%)</label>
                        <input type="number" id="commission" wire:model="commission" min="0" max="100" step="0.01">
                        @error('commission') <span class="error">{{ $message }}</span> @enderror
                        <p class="setting-help">Percentage commission charged on each transaction.</p>
                    </div>
                    
                    <div class="form-group">
                        <label class="toggle-label" for="requireApproval">
                            <span>Require Announcements Approval</span>
                            <div class="toggle-switch">
                                <input type="checkbox" id="requireApproval" wire:model="requireApproval">
                                <span class="toggle-slider"></span>
                            </div>
                        </label>
                        <p class="setting-help">If enabled, new announcements require admin approval before being published.</p>
                    </div>
                    
                    <div class="form-group">
                        <label class="toggle-label" for="maintenanceMode">
                            <span>Maintenance Mode</span>
                            <div class="toggle-switch">
                                <input type="checkbox" id="maintenanceMode" wire:model="maintenanceMode">
                                <span class="toggle-slider"></span>
                            </div>
                        </label>
                        <p class="setting-help">When enabled, only administrators can access the site.</p>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="button">Save Settings</button>
        </form>
    </div>
</div>