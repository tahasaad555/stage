@extends('layouts.admin')

@section('title', 'System Settings')

@push('styles')
<style>
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.settings-tab {
    transition: all 0.3s ease;
}

.settings-tab.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.settings-content {
    display: none;
}

.settings-content.active {
    display: block;
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.card-animation {
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-2">⚙️ System Settings</h1>
                <p class="text-xl opacity-90 mb-4">Configure and manage your AgriTerre platform</p>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-cog mr-2"></i>
                        <span>General Configuration</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>Security Settings</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-envelope mr-2"></i>
                        <span>Email Configuration</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                        <i class="fas fa-database mr-2"></i>
                        <span>Backup Management</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-tools text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Navigation and Content -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Settings Navigation -->
        <div class="lg:col-span-1">
            <div class="glass-effect rounded-2xl p-6 shadow-xl card-animation">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Settings Menu</h3>
                <nav class="space-y-2">
                    <button onclick="showTab('general')" class="settings-tab active w-full text-left px-4 py-3 rounded-xl transition-all flex items-center">
                        <i class="fas fa-home mr-3"></i>
                        General Settings
                    </button>
                    <button onclick="showTab('commission')" class="settings-tab w-full text-left px-4 py-3 rounded-xl transition-all flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-percentage mr-3"></i>
                        Commission Settings
                    </button>
                    <button onclick="showTab('email')" class="settings-tab w-full text-left px-4 py-3 rounded-xl transition-all flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-envelope mr-3"></i>
                        Email Settings
                    </button>
                    <button onclick="showTab('security')" class="settings-tab w-full text-left px-4 py-3 rounded-xl transition-all flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-shield-alt mr-3"></i>
                        Security Settings
                    </button>
                    <button onclick="showTab('backup')" class="settings-tab w-full text-left px-4 py-3 rounded-xl transition-all flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-database mr-3"></i>
                        Backup & Maintenance
                    </button>
                    <button onclick="showTab('system')" class="settings-tab w-full text-left px-4 py-3 rounded-xl transition-all flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-info-circle mr-3"></i>
                        System Information
                    </button>
                </nav>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="lg:col-span-3">
            <!-- General Settings -->
            <div id="general-content" class="settings-content active">
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-animation">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">🏠 General Settings</h3>
                            <p class="text-gray-600">Basic site configuration and information</p>
                        </div>
                        <button onclick="updateSettings('general')" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                    
                    <form id="general-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                                <input type="text" name="site_name" value="AgriTerre Morocco" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                                <input type="email" name="contact_email" value="contact@agriterre.ma" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                                <input type="text" name="contact_phone" value="+212 5 22 12 34 56" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                                <select name="currency" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="MAD" selected>MAD - Moroccan Dirham</option>
                                    <option value="EUR">EUR - Euro</option>
                                    <option value="USD">USD - US Dollar</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                <select name="timezone" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="Africa/Casablanca" selected>Africa/Casablanca</option>
                                    <option value="Europe/Paris">Europe/Paris</option>
                                    <option value="UTC">UTC</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                                <select name="language" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="en" selected>English</option>
                                    <option value="fr">Français</option>
                                    <option value="ar">العربية</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                            <textarea name="site_description" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">AgriTerre is Morocco's premier agricultural land marketplace, connecting farmers, investors, and landowners for sustainable agricultural development.</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="address" rows="2" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">123 Boulevard Hassan II, Casablanca 20000, Morocco</textarea>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Commission Settings -->
            <div id="commission-content" class="settings-content">
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-animation">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">💰 Commission Settings</h3>
                            <p class="text-gray-600">Configure transaction commission rates</p>
                        </div>
                        <button onclick="updateSettings('commission')" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl hover:from-green-600 hover:to-green-700 transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                    
                    <form id="commission-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Commission Type</label>
                                <select name="commission_type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="percentage" selected>Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Commission Rate (%)</label>
                                <input type="number" name="commission_rate" value="5" step="0.1" min="0" max="100" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Commission (MAD)</label>
                                <input type="number" name="minimum_commission" value="1000" min="0" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Commission (MAD)</label>
                                <input type="number" name="maximum_commission" value="50000" min="0" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl">
                            <h4 class="font-semibold text-green-800 mb-3">Commission Calculator Preview</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-green-700">
                                <div class="bg-white bg-opacity-60 p-3 rounded-lg">
                                    <p class="font-medium">Transaction: 100,000 MAD</p>
                                    <p id="commission-preview-100k" class="font-bold text-green-800">Commission: 5,000 MAD</p>
                                </div>
                                <div class="bg-white bg-opacity-60 p-3 rounded-lg">
                                    <p class="font-medium">Transaction: 500,000 MAD</p>
                                    <p id="commission-preview-500k" class="font-bold text-green-800">Commission: 25,000 MAD</p>
                                </div>
                                <div class="bg-white bg-opacity-60 p-3 rounded-lg">
                                    <p class="font-medium">Transaction: 1,000,000 MAD</p>
                                    <p id="commission-preview-1m" class="font-bold text-green-800">Commission: 50,000 MAD</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Email Settings -->
            <div id="email-content" class="settings-content">
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-animation">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">📧 Email Settings</h3>
                            <p class="text-gray-600">Configure email delivery settings</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button onclick="testEmail()" class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-4 py-3 rounded-xl hover:from-yellow-600 hover:to-yellow-700 transition-all flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>Test Email
                            </button>
                            <button onclick="updateSettings('email')" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all flex items-center">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </div>
                    
                    <form id="email-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mail Driver</label>
                                <select name="mail_driver" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="smtp" selected>SMTP</option>
                                    <option value="sendmail">Sendmail</option>
                                    <option value="mailgun">Mailgun</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mail Host</label>
                                <input type="text" name="mail_host" value="smtp.gmail.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mail Port</label>
                                <input type="number" name="mail_port" value="587" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                                <select name="mail_encryption" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">None</option>
                                    <option value="tls" selected>TLS</option>
                                    <option value="ssl">SSL</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <input type="text" name="mail_username" value="noreply@agriterre.ma" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <input type="password" name="mail_password" value="••••••••••••" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">From Address</label>
                                <input type="email" name="mail_from_address" value="noreply@agriterre.ma" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                                <input type="text" name="mail_from_name" value="AgriTerre Morocco" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <!-- Email Templates Section -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Email Templates</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl">
                                    <h5 class="font-semibold text-purple-800 mb-2">Welcome Email</h5>
                                    <p class="text-sm text-purple-600 mb-3">Sent to new users upon registration</p>
                                    <button class="text-purple-700 hover:text-purple-900 text-sm font-medium">Edit Template</button>
                                </div>
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl">
                                    <h5 class="font-semibold text-blue-800 mb-2">Transaction Confirmation</h5>
                                    <p class="text-sm text-blue-600 mb-3">Sent when transactions are completed</p>
                                    <button class="text-blue-700 hover:text-blue-900 text-sm font-medium">Edit Template</button>
                                </div>
                                <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl">
                                    <h5 class="font-semibold text-green-800 mb-2">Property Notification</h5>
                                    <p class="text-sm text-green-600 mb-3">Alerts for new properties and updates</p>
                                    <button class="text-green-700 hover:text-green-900 text-sm font-medium">Edit Template</button>
                                </div>
                                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 rounded-xl">
                                    <h5 class="font-semibold text-yellow-800 mb-2">Password Reset</h5>
                                    <p class="text-sm text-yellow-600 mb-3">Password recovery emails</p>
                                    <button class="text-yellow-700 hover:text-yellow-900 text-sm font-medium">Edit Template</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Settings -->
            <div id="security-content" class="settings-content">
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-animation">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">🔒 Security Settings</h3>
                            <p class="text-gray-600">Configure security and authentication settings</p>
                        </div>
                        <button onclick="updateSettings('security')" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-3 rounded-xl hover:from-red-600 hover:to-red-700 transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                    
                    <form id="security-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Session Lifetime (minutes)</label>
                                <input type="number" name="session_lifetime" value="120" min="1" max="1440" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Password Length</label>
                                <input type="number" name="password_min_length" value="8" min="6" max="50" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Login Attempts</label>
                                <input type="number" name="max_login_attempts" value="5" min="3" max="10" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lockout Duration (minutes)</label>
                                <input type="number" name="lockout_duration" value="15" min="1" max="60" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="require_email_verification" checked class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label class="ml-3 block text-sm font-medium text-gray-700">Require Email Verification</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="enable_two_factor" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label class="ml-3 block text-sm font-medium text-gray-700">Enable Two-Factor Authentication</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="force_https" checked class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label class="ml-3 block text-sm font-medium text-gray-700">Force HTTPS</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="enable_rate_limiting" checked class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label class="ml-3 block text-sm font-medium text-gray-700">Enable Rate Limiting</label>
                            </div>
                        </div>

                        <!-- Security Logs Section -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Recent Security Events</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                                    <div class="flex items-center">
                                        <i class="fas fa-shield-check text-green-600 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-800">Successful Admin Login</p>
                                            <p class="text-sm text-gray-600">IP: 192.168.1.100 • User: admin@agriterre.com</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">2 minutes ago</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-800">Failed Login Attempt</p>
                                            <p class="text-sm text-gray-600">IP: 185.220.102.4 • Attempts: 3</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">1 hour ago</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-800">New User Registration</p>
                                            <p class="text-sm text-gray-600">User: client@example.com • Status: Verified</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">3 hours ago</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Backup Settings -->
            <div id="backup-content" class="settings-content">
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-animation">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">💾 Backup & Maintenance</h3>
                            <p class="text-gray-600">Configure backup settings and system maintenance</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button onclick="runBackup()" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-3 rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all flex items-center">
                                <i class="fas fa-download mr-2"></i>Run Backup
                            </button>
                            <button onclick="clearCache()" class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-4 py-3 rounded-xl hover:from-yellow-600 hover:to-yellow-700 transition-all flex items-center">
                                <i class="fas fa-broom mr-2"></i>Clear Cache
                            </button>
                            <button onclick="optimizeSystem()" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-4 py-3 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all flex items-center">
                                <i class="fas fa-rocket mr-2"></i>Optimize
                            </button>
                            <button onclick="updateSettings('backup')" class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-indigo-600 hover:to-indigo-700 transition-all flex items-center">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </div>
                    
                    <form id="backup-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Backup Frequency</label>
                                <select name="backup_frequency" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="daily" selected>Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Retention Days</label>
                                <input type="number" name="backup_retention_days" value="30" min="1" max="365" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Storage Location</label>
                                <select name="backup_storage" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="local" selected>Local Storage</option>
                                    <option value="s3">Amazon S3</option>
                                    <option value="google">Google Cloud</option>
                                    <option value="dropbox">Dropbox</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Backup Time</label>
                                <input type="time" name="backup_time" value="02:00" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="backup_enabled" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label class="ml-3 block text-sm font-medium text-gray-700">Enable Automatic Backups</label>
                        </div>
                    </form>

                    <!-- Maintenance Mode Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Maintenance Mode</h4>
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-semibold text-gray-800 mb-2">System Maintenance</h5>
                                    <p class="text-sm text-gray-600 mb-4">Put the site in maintenance mode to perform updates or repairs</p>
                                    <div class="flex items-center space-x-4">
                                        <button onclick="enableMaintenanceMode()" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg hover:from-red-600 hover:to-red-700 transition-all text-sm">
                                            <i class="fas fa-tools mr-2"></i>Enable Maintenance
                                        </button>
                                        <button onclick="disableMaintenanceMode()" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all text-sm">
                                            <i class="fas fa-check mr-2"></i>Disable Maintenance
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div id="maintenance-status" class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">Status: Active</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Backups -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Recent Backups</h4>
                        <div class="space-y-3" id="backup-list">
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                                <div class="flex items-center">
                                    <i class="fas fa-file-archive text-green-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-gray-800">agriterre_backup_2025_06_08.sql</p>
                                        <p class="text-sm text-gray-600">Size: 2.4 MB • Created: Today at 09:30</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Download</button>
                                    <button class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                                <div class="flex items-center">
                                    <i class="fas fa-file-archive text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-gray-800">agriterre_backup_2025_06_01.sql</p>
                                        <p class="text-sm text-gray-600">Size: 2.1 MB • Created: 1 week ago</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Download</button>
                                    <button class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl">
                                <div class="flex items-center">
                                    <i class="fas fa-file-archive text-purple-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-gray-800">agriterre_backup_2025_05_25.sql</p>
                                        <p class="text-sm text-gray-600">Size: 1.9 MB • Created: 2 weeks ago</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Download</button>
                                    <button class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Optimization Tools -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">System Optimization</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl">
                                <h5 class="font-semibold text-blue-800 mb-2">Database Optimization</h5>
                                <p class="text-sm text-blue-600 mb-3">Optimize database tables and clean up unused data</p>
                                <button onclick="optimizeDatabase()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all text-sm">
                                    <i class="fas fa-database mr-2"></i>Optimize Database
                                </button>
                            </div>
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl">
                                <h5 class="font-semibold text-purple-800 mb-2">Cache Management</h5>
                                <p class="text-sm text-purple-600 mb-3">Clear application cache and rebuild</p>
                                <button onclick="rebuildCache()" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-all text-sm">
                                    <i class="fas fa-sync-alt mr-2"></i>Rebuild Cache
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div id="system-content" class="settings-content">
                <div class="glass-effect rounded-2xl p-8 shadow-xl card-animation">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">ℹ️ System Information</h3>
                            <p class="text-gray-600">View system status and server information</p>
                        </div>
                        <button onclick="refreshSystemInfo()" class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-6 py-3 rounded-xl hover:from-cyan-600 hover:to-cyan-700 transition-all flex items-center">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh Info
                        </button>
                    </div>
                    
                    <div id="system-info" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl">
                            <h4 class="font-semibold text-blue-800 mb-4 flex items-center">
                                <i class="fas fa-server mr-2"></i>Server Information
                            </h4>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-700">PHP Version:</span>
                                    <span class="font-medium text-blue-900">8.2.10</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Laravel Version:</span>
                                    <span class="font-medium text-blue-900">11.x</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Server:</span>
                                    <span class="font-medium text-blue-900">Apache/2.4.56</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Database:</span>
                                    <span class="font-medium text-blue-900">MySQL 8.0.34</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl">
                            <h4 class="font-semibold text-green-800 mb-4 flex items-center">
                                <i class="fas fa-memory mr-2"></i>System Resources
                            </h4>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-green-700">Memory Limit:</span>
                                    <span class="font-medium text-green-900">512M</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-700">Max Execution:</span>
                                    <span class="font-medium text-green-900">300s</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-700">Upload Limit:</span>
                                    <span class="font-medium text-green-900">64M</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-700">Disk Space:</span>
                                    <span class="font-medium text-green-900">45.2 GB Free</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-xl">
                            <h4 class="font-semibold text-purple-800 mb-4 flex items-center">
                                <i class="fas fa-cogs mr-2"></i>Configuration
                            </h4>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-purple-700">Cache Driver:</span>
                                    <span class="font-medium text-purple-900">Redis</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-purple-700">Queue Driver:</span>
                                    <span class="font-medium text-purple-900">Database</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-purple-700">Session Driver:</span>
                                    <span class="font-medium text-purple-900">Database</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-purple-700">Mail Driver:</span>
                                    <span class="font-medium text-purple-900">SMTP</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-6 rounded-xl">
                            <h4 class="font-semibold text-yellow-800 mb-4 flex items-center">
                                <i class="fas fa-chart-pie mr-2"></i>Performance Metrics
                            </h4>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-yellow-700">Avg Response Time:</span>
                                    <span class="font-medium text-yellow-900">245ms</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-yellow-700">CPU Usage:</span>
                                    <span class="font-medium text-yellow-900">12%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-yellow-700">Memory Usage:</span>
                                    <span class="font-medium text-yellow-900">68%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-yellow-700">Uptime:</span>
                                    <span class="font-medium text-yellow-900">15 days</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Health Status -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">System Health</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-green-800">Database</p>
                                        <p class="text-sm text-green-600">Connected</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-green-800">Cache</p>
                                        <p class="text-sm text-green-600">Active</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-exclamation text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-yellow-800">Queue</p>
                                        <p class="text-sm text-yellow-600">3 Jobs Pending</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Statistics -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Application Statistics</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                                <div class="text-2xl font-bold text-blue-600 mb-1">{{ \App\Models\User::count() }}</div>
                                <div class="text-xs text-gray-600">Total Users</div>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                                <div class="text-2xl font-bold text-green-600 mb-1">{{ \App\Models\TerreAgricole::count() }}</div>
                                <div class="text-xs text-gray-600">Properties</div>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl">
                                <div class="text-2xl font-bold text-purple-600 mb-1">{{ \App\Models\Transaction::count() }}</div>
                                <div class="text-xs text-gray-600">Transactions</div>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl">
                                <div class="text-2xl font-bold text-yellow-600 mb-1">{{ \App\Models\Annonce::count() }}</div>
                                <div class="text-xs text-gray-600">Listings</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Tab switching functionality
function showTab(tabName) {
    // Hide all content
    document.querySelectorAll('.settings-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active from all tabs
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.classList.remove('active');
        tab.classList.add('text-gray-700', 'hover:bg-gray-100');
        tab.classList.remove('text-white');
    });
    
    // Show selected content
    document.getElementById(tabName + '-content').classList.add('active');
    
    // Activate selected tab
    event.target.classList.add('active');
    event.target.classList.remove('text-gray-700', 'hover:bg-gray-100');
    event.target.classList.add('text-white');
    
    // Load system info if system tab is selected
    if (tabName === 'system') {
        refreshSystemInfo();
    }
}

// Update settings function
async function updateSettings(section) {
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<span class="loading-spinner"></span>Saving...';
    button.disabled = true;
    
    try {
        const form = document.getElementById(section + '-form');
        const formData = new FormData(form);
        
        // Convert FormData to regular object
        const data = {};
        formData.forEach((value, key) => {
            if (form.querySelector(`[name="${key}"]`).type === 'checkbox') {
                data[key] = form.querySelector(`[name="${key}"]`).checked;
            } else {
                data[key] = value;
            }
        });
        
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        showNotification(`${section.charAt(0).toUpperCase() + section.slice(1)} settings updated successfully!`, 'success');
        
        // Update commission preview if it's commission settings
        if (section === 'commission') {
            updateCommissionPreview();
        }
    } catch (error) {
        showNotification('An error occurred: ' + error.message, 'error');
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Run backup function
async function runBackup() {
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<span class="loading-spinner"></span>Running...';
    button.disabled = true;
    
    try {
        // Simulate backup process
        await new Promise(resolve => setTimeout(resolve, 3000));
        
        showNotification('Backup completed successfully!', 'success');
        
        // Add new backup to the list
        const backupList = document.getElementById('backup-list');
        const newBackup = document.createElement('div');
        newBackup.className = 'flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl';
        newBackup.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-file-archive text-green-600 mr-3"></i>
                <div>
                    <p class="font-medium text-gray-800">agriterre_backup_${new Date().toISOString().slice(0,10).replace(/-/g,'_')}.sql</p>
                    <p class="text-sm text-gray-600">Size: 2.6 MB • Created: Just now</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Download</button>
                <button class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
            </div>
        `;
        backupList.prepend(newBackup);
    } catch (error) {
        showNotification('Backup failed: ' + error.message, 'error');
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Clear cache function
async function clearCache() {
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<span class="loading-spinner"></span>Clearing...';
    button.disabled = true;
    
    try {
        await new Promise(resolve => setTimeout(resolve, 2000));
        showNotification('Cache cleared successfully!', 'success');
    } catch (error) {
        showNotification('Failed to clear cache: ' + error.message, 'error');
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Optimize system function
async function optimizeSystem() {
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<span class="loading-spinner"></span>Optimizing...';
    button.disabled = true;
    
    try {
        await new Promise(resolve => setTimeout(resolve, 4000));
        showNotification('System optimization completed!', 'success');
    } catch (error) {
        showNotification('Failed to optimize system: ' + error.message, 'error');
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Enable maintenance mode
async function enableMaintenanceMode() {
    if (!confirm('Are you sure you want to enable maintenance mode? This will make the site unavailable to users.')) {
        return;
    }
    
    try {
        await new Promise(resolve => setTimeout(resolve, 1000));
        showNotification('Maintenance mode enabled successfully!', 'success');
        updateMaintenanceStatus(true);
    } catch (error) {
        showNotification('Failed to enable maintenance mode: ' + error.message, 'error');
    }
}

// Disable maintenance mode
async function disableMaintenanceMode() {
    try {
        await new Promise(resolve => setTimeout(resolve, 1000));
        showNotification('Maintenance mode disabled successfully!', 'success');
        updateMaintenanceStatus(false);
    } catch (error) {
        showNotification('Failed to disable maintenance mode: ' + error.message, 'error');
    }
}

// Update maintenance status display
function updateMaintenanceStatus(enabled) {
    const statusDiv = document.getElementById('maintenance-status');
    const statusText = statusDiv.nextElementSibling;
    
    if (enabled) {
        statusDiv.className = 'w-16 h-16 bg-red-500 rounded-full flex items-center justify-center';
        statusDiv.innerHTML = '<i class="fas fa-tools text-white text-xl"></i>';
        statusText.innerHTML = '<p class="text-sm text-gray-600 mt-2">Status: Maintenance</p>';
    } else {
        statusDiv.className = 'w-16 h-16 bg-green-500 rounded-full flex items-center justify-center';
        statusDiv.innerHTML = '<i class="fas fa-check text-white text-xl"></i>';
        statusText.innerHTML = '<p class="text-sm text-gray-600 mt-2">Status: Active</p>';
    }
}

// Test email function
async function testEmail() {
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<span class="loading-spinner"></span>Sending...';
    button.disabled = true;
    
    try {
        // Simulate email test
        await new Promise(resolve => setTimeout(resolve, 2000));
        showNotification('Test email sent successfully!', 'success');
    } catch (error) {
        showNotification('Failed to send test email: ' + error.message, 'error');
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Optimize database function
async function optimizeDatabase() {
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<span class="loading-spinner"></span>Optimizing...';
    button.disabled = true;
    
    try {
        await new Promise(resolve => setTimeout(resolve, 3000));
        showNotification('Database optimization completed!', 'success');
    } catch (error) {
        showNotification('Failed to optimize database: ' + error.message, 'error');
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Rebuild cache function
async function rebuildCache() {
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<span class="loading-spinner"></span>Rebuilding...';
    button.disabled = true;
    
    try {
        await new Promise(resolve => setTimeout(resolve, 2500));
        showNotification('Cache rebuilt successfully!', 'success');
    } catch (error) {
        showNotification('Failed to rebuild cache: ' + error.message, 'error');
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Refresh system info
function refreshSystemInfo() {
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<span class="loading-spinner"></span>Refreshing...';
    button.disabled = true;
    
    setTimeout(() => {
        // Simulate data refresh
        const metrics = document.querySelectorAll('#system-info .font-medium');
        metrics.forEach(metric => {
            const parent = metric.parentElement;
            if (parent.textContent.includes('CPU Usage')) {
                metric.textContent = Math.floor(Math.random() * 30) + 5 + '%';
            } else if (parent.textContent.includes('Memory Usage')) {
                metric.textContent = Math.floor(Math.random() * 40) + 50 + '%';
            } else if (parent.textContent.includes('Response Time')) {
                metric.textContent = Math.floor(Math.random() * 200) + 150 + 'ms';
            }
        });
        
        button.innerHTML = originalContent;
        button.disabled = false;
        showNotification('System information refreshed!', 'success');
    }, 1500);
}

// Update commission preview
function updateCommissionPreview() {
    const rate = parseFloat(document.querySelector('[name="commission_rate"]').value) || 0;
    const type = document.querySelector('[name="commission_type"]').value;
    const minimum = parseFloat(document.querySelector('[name="minimum_commission"]').value) || 0;
    const maximum = parseFloat(document.querySelector('[name="maximum_commission"]').value) || 0;
    
    const amounts = [100000, 500000, 1000000];
    const previews = ['commission-preview-100k', 'commission-preview-500k', 'commission-preview-1m'];
    
    amounts.forEach((amount, index) => {
        let commission;
        
        if (type === 'percentage') {
            commission = amount * rate / 100;
            commission = Math.max(commission, minimum);
            if (maximum > 0) {
                commission = Math.min(commission, maximum);
            }
        } else {
            commission = rate;
        }
        
        const element = document.getElementById(previews[index]);
        if (element) {
            element.textContent = `Commission: ${commission.toLocaleString()} MAD`;
        }
    });
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform transition-all duration-500 ${getNotificationClasses(type)}`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${getNotificationIcon(type)} mr-3"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:opacity-75">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

function getNotificationClasses(type) {
    switch (type) {
        case 'success': return 'bg-gradient-to-r from-green-500 to-green-600 text-white';
        case 'error': return 'bg-gradient-to-r from-red-500 to-red-600 text-white';
        case 'warning': return 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white';
        default: return 'bg-gradient-to-r from-blue-500 to-blue-600 text-white';
    }
}

function getNotificationIcon(type) {
    switch (type) {
        case 'success': return 'fa-check-circle';
        case 'error': return 'fa-exclamation-circle';
        case 'warning': return 'fa-exclamation-triangle';
        default: return 'fa-info-circle';
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time commission preview updates
    const commissionInputs = document.querySelectorAll('[name="commission_rate"], [name="commission_type"], [name="minimum_commission"], [name="maximum_commission"]');
    commissionInputs.forEach(input => {
        input.addEventListener('input', updateCommissionPreview);
        input.addEventListener('change', updateCommissionPreview);
    });
    
    // Initialize commission preview
    updateCommissionPreview();
    
    // Animate cards on load
    const cards = document.querySelectorAll('.card-animation');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.click();
            }
        });
    });
    
    // Auto-save functionality for form inputs
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Visual feedback for unsaved changes
            if (!input.classList.contains('border-yellow-300')) {
                input.classList.add('border-yellow-300', 'ring-yellow-100');
                setTimeout(() => {
                    input.classList.remove('border-yellow-300', 'ring-yellow-100');
                }, 2000);
            }
        });
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save current tab settings
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const activeTab = document.querySelector('.settings-tab.active');
            if (activeTab) {
                const tabName = activeTab.textContent.trim().toLowerCase().split(' ')[0];
                updateSettings(tabName);
            }
        }
        
        // Ctrl/Cmd + B to run backup
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            runBackup();
        }
        
        // Ctrl/Cmd + R to refresh system info (if on system tab)
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            const systemTab = document.getElementById('system-content');
            if (systemTab && systemTab.classList.contains('active')) {
                refreshSystemInfo();
            }
        }
    });
    
    // Add tooltips for form fields
    const tooltips = {
        'session_lifetime': 'How long users stay logged in without activity',
        'password_min_length': 'Minimum number of characters required for passwords',
        'max_login_attempts': 'Number of failed login attempts before account lockout',
        'backup_retention_days': 'How long to keep backup files before automatic deletion',
        'commission_rate': 'Percentage or fixed amount charged per transaction'
    };
    
    Object.keys(tooltips).forEach(name => {
        const input = document.querySelector(`[name="${name}"]`);
        if (input) {
            input.setAttribute('title', tooltips[name]);
        }
    });
    
    // Real-time system monitoring (simulation)
    setInterval(() => {
        const cpuElement = document.querySelector('#system-info').querySelector('[class*="text-yellow-900"]');
        if (cpuElement && cpuElement.textContent.includes('%')) {
            const newCpuUsage = Math.floor(Math.random() * 20) + 10;
            cpuElement.textContent = newCpuUsage + '%';
            
            // Update color based on usage
            const container = cpuElement.closest('.rounded-xl');
            if (newCpuUsage > 80) {
                container.className = container.className.replace('yellow', 'red');
            } else if (newCpuUsage > 60) {
                container.className = container.className.replace(/red|green/g, 'yellow');
            } else {
                container.className = container.className.replace(/red|yellow/g, 'green');
            }
        }
    }, 10000); // Update every 10 seconds
    
    // Welcome message
    setTimeout(() => {
        showNotification('Welcome to System Settings! Configure your AgriTerre platform here.', 'info');
    }, 1000);
});

// Export settings function
function exportSettings() {
    const settings = {};
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const formData = new FormData(form);
        const section = form.id.replace('-form', '');
        settings[section] = {};
        
        formData.forEach((value, key) => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input && input.type === 'checkbox') {
                settings[section][key] = input.checked;
            } else {
                settings[section][key] = value;
            }
        });
    });
    
    const dataStr = JSON.stringify(settings, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
    
    const exportFileDefaultName = 'agriterre_settings_' + new Date().toISOString().slice(0,10) + '.json';
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
    
    showNotification('Settings exported successfully!', 'success');
}

// Import settings function
function importSettings(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const settings = JSON.parse(e.target.result);
            
            Object.keys(settings).forEach(section => {
                const form = document.getElementById(section + '-form');
                if (form) {
                    Object.keys(settings[section]).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            if (input.type === 'checkbox') {
                                input.checked = settings[section][key];
                            } else {
                                input.value = settings[section][key];
                            }
                        }
                    });
                }
            });
            
            // Update commission preview after import
            updateCommissionPreview();
            
            showNotification('Settings imported successfully!', 'success');
        } catch (error) {
            showNotification('Failed to import settings: Invalid file format', 'error');
        }
    };
    
    reader.readAsText(file);
}

// Add import/export buttons
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.bg-gradient-to-r.from-purple-600');
    if (header) {
        const exportImportDiv = document.createElement('div');
        exportImportDiv.className = 'mt-4 flex items-center space-x-4';
        exportImportDiv.innerHTML = `
            <button onclick="exportSettings()" class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-lg hover:bg-opacity-30 transition-all text-sm flex items-center">
                <i class="fas fa-download mr-2"></i>Export Settings
            </button>
            <label class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-lg hover:bg-opacity-30 transition-all text-sm flex items-center cursor-pointer">
                <i class="fas fa-upload mr-2"></i>Import Settings
                <input type="file" accept=".json" onchange="importSettings(event)" class="hidden">
            </label>
        `;
        header.querySelector('.flex-1').appendChild(exportImportDiv);
    }
});
</script>
@endpush

@endsection