<div class="language-switcher">
    <div class="dropdown">
        <button class="btn btn-language dropdown-toggle" type="button" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="language-icon">🌐</span>
            <span class="current-locale">{{ session('locale', 'en') === 'th' ? 'ไทย' : 'English' }}</span>
            <span class="dropdown-arrow">▼</span>
        </button>
        <div class="dropdown-menu" aria-labelledby="languageDropdown">
            <a class="dropdown-item language-option {{ session('locale', 'en') === 'en' ? 'active' : '' }}" href="#" data-locale="en">
                <span class="flag-icon">🇺🇸</span>
                <span class="language-text">English</span>
            </a>
            <a class="dropdown-item language-option {{ session('locale', 'en') === 'th' ? 'active' : '' }}" href="#" data-locale="th">
                <span class="flag-icon">🇹🇭</span>
                <span class="language-text">ไทย</span>
            </a>
        </div>
    </div>
</div>

<style>
.language-switcher {
    position: fixed;
    top: 15px;
    right: 15px;
    z-index: 9999;
}

.btn-language {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    padding: 10px 18px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 120px;
    justify-content: space-between;
}

.btn-language:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    transform: translateY(-2px);
    color: white;
}

.btn-language:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-language:active {
    transform: translateY(0);
}

.language-icon {
    font-size: 16px;
    opacity: 0.9;
}

.current-locale {
    font-weight: 600;
    letter-spacing: 0.5px;
}

.dropdown-arrow {
    font-size: 10px;
    opacity: 0.8;
    transition: transform 0.2s ease;
}

.btn-language[aria-expanded="true"] .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-menu {
    min-width: 160px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border: none;
    padding: 8px;
    margin-top: 8px;
    background: white;
}

.dropdown-item {
    padding: 12px 16px;
    font-size: 14px;
    border-radius: 8px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 2px;
    color: #333;
    text-decoration: none;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
    color: #667eea;
    transform: translateX(2px);
}

.dropdown-item.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
}

.dropdown-item.active:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    color: white;
}

.flag-icon {
    font-size: 18px;
    width: 20px;
    text-align: center;
}

.language-text {
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .language-switcher {
        top: 10px;
        right: 10px;
    }
    
    .btn-language {
        padding: 8px 14px;
        font-size: 13px;
        min-width: 100px;
    }
    
    .dropdown-menu {
        min-width: 140px;
    }
}

/* Animation for language switch */
.language-switcher.switching .btn-language {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>
