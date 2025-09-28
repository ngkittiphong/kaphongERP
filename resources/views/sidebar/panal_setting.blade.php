<div role="tabpanel" class="tab-pane settings fade" id="settings">
    <ul class="sidebar-accordion">
    
        <li class="list-title">Company</li>
        <li>
            <a href="index2.htm#"><i class="icon-display4"></i><span class="list-label"> Company</span></a>
            <ul>
                <li><a href="/setting/company_profile">Company Setup</a></li>								
            </ul>
        </li>
        
        <li class="list-title">Doc Setup</li>						

        <li>
            <a href=""><i class="icon-alignment-unalign"></i> <span>Doc</span></a>
            <ul>							
                <li><a href="/setting/delivery_note">delivery note setup </a></li>																	
            </ul>
        </li>
        
        <!-- Language Switcher Section -->
        <li class="list-title">{{ __t('common.language', 'Language') }}</li>
        <li class="language-switcher-sidebar">
            <div class="language-switcher-container">
                <div class="current-language-display">
                    <i class="icon-earth"></i>
                    <span class="current-locale">{{ session('locale', 'en') === 'th' ? 'ไทย' : 'English' }}</span>
                </div>
                <div class="language-options">
                    <a href="#" class="language-option {{ session('locale', 'en') === 'en' ? 'active' : '' }}" data-locale="en">
                        <span class="flag-icon">🇺🇸</span>
                        <span class="language-text">English</span>
                    </a>
                    <a href="#" class="language-option {{ session('locale', 'en') === 'th' ? 'active' : '' }}" data-locale="th">
                        <span class="flag-icon">🇹🇭</span>
                        <span class="language-text">ไทย</span>
                    </a>
                </div>
            </div>
        </li>
</div>

<style>
/* Sidebar Language Switcher Styles */
.language-switcher-sidebar {
    padding: 0 !important;
    margin: 10px 0;
}

.language-switcher-container {
    padding: 15px 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    margin: 0 10px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.current-language-display {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    padding: 8px 12px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 6px;
    color: #e0e0e0;
    font-size: 14px;
    font-weight: 500;
}

.current-language-display i {
    color: #4a90e2;
    font-size: 16px;
}

.current-locale {
    font-weight: 600;
    letter-spacing: 0.5px;
}

.language-options {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.language-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: #b0b0b0;
    font-size: 13px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.language-option:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateX(3px);
}

.language-option.active {
    background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
    color: #ffffff;
    border-color: #4a90e2;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(74, 144, 226, 0.3);
}

.language-option.active:hover {
    background: linear-gradient(135deg, #357abd 0%, #2c5aa0 100%);
    transform: translateX(3px);
}

.flag-icon {
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.language-text {
    font-weight: 500;
}

/* Animation for switching */
.language-switcher-sidebar.switching .current-language-display {
    animation: pulse-sidebar 1s infinite;
}

@keyframes pulse-sidebar {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .language-switcher-container {
        margin: 0 5px;
        padding: 12px 15px;
    }
    
    .current-language-display {
        font-size: 13px;
    }
    
    .language-option {
        font-size: 12px;
        padding: 8px 10px;
    }
}
</style>