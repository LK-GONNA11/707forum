/**
 * 707Forum Core Security Script
 * Version 0.7.07
 */

// Configuration sécurisée
const config = {
    security: {
        debugDetection: true,
        keypressLogging: false,
        sessionProtection: true
    },
    ui: {
        darkMode: true,
        animations: true
    }
};

// Module de sécurité
const Security = (() => {
    let threatLevel = 0;
    const suspiciousPatterns = [
        'devtools', 'debugger', 'inspect', 'console', 'javascript:'
    ];

    // Détection d'outils de développement
    const detectDevTools = () => {
        const devtools = /./;
        devtools.toString = () => {
            threatLevel += 10;
            logSecurityEvent('DevTools detected');
            return '';
        };
        console.log('%c', devtools);
    };

    // Surveillance des événements suspects
    const monitorEvents = () => {
        document.addEventListener('contextmenu', (e) => {
            threatLevel += 5;
            logSecurityEvent('Right-click attempted');
        });

        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.shiftKey && e.key === 'I') {
                threatLevel += 15;
                logSecurityEvent('DevTools shortcut detected');
            }
        });
    };

    // Journalisation sécurisée
    const logSecurityEvent = (event) => {
        if (!config.security.debugDetection) return;
        
        const logEntry = {
            timestamp: new Date().toISOString(),
            ip: window.userIP || 'unknown',
            event,
            threatLevel,
            userAgent: navigator.userAgent
        };

        fetch('/api/log', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Security-Token': '707forum_' + btoa(navigator.userAgent)
            },
            body: JSON.stringify(logEntry)
        }).catch(() => {
            // Fallback en cas d'échec
            console.warn('Security event:', event);
        });
    };

    // Protection de session
    const protectSession = () => {
        if (!config.security.sessionProtection) return;
        
        setInterval(() => {
            fetch('/api/session/verify', {
                credentials: 'include'
            }).then(res => {
                if (res.status === 403) {
                    window.location.href = '/hold.php';
                }
            });
        }, 30000);
    };

    // Initialisation
    const init = () => {
        if (config.security.debugDetection) {
            setInterval(detectDevTools, 1000);
        }
        monitorEvents();
        protectSession();
    };

    return {
        init,
        logSecurityEvent,
        getThreatLevel: () => threatLevel
    };
})();

// Module UI
const UI = (() => {
    // Animation de glitch
    const applyGlitchEffect = () => {
        document.querySelectorAll('.glitch').forEach(el => {
            el.addEventListener('mouseover', () => {
                el.style.textShadow = '2px 0 #ff00ff, -2px 0 #00ff7a';
                setTimeout(() => {
                    el.style.textShadow = 'none';
                }, 200);
            });
        });
    };

    // Mode sombre dynamique
    const handleDarkMode = () => {
        if (!config.ui.darkMode) return;
        
        document.documentElement.style.setProperty('--bg-color', '#0a0a0a');
        document.documentElement.style.setProperty('--text-color', '#e0e0e0');
    };

    // Initialisation
    const init = () => {
        if (config.ui.animations) {
            applyGlitchEffect();
        }
        handleDarkMode();
    };

    return {
        init
    };
})();

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', () => {
    Security.init();
    UI.init();
    
    // Masquer les erreurs en production
    if (window.location.hostname !== 'localhost') {
        window.onerror = () => true;
    }
});

// API simplifiée
window.ForumAPI = {
    refreshPosts: async () => {
        const res = await fetch('/api/posts/latest');
        return res.json();
    },
    submitPost: async (content) => {
        const res = await fetch('/api/posts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ content })
        });
        return res.json();
    }
};

// Export pour les tests (en développement seulement)
if (process.env.NODE_ENV === 'development') {
    module.exports = { Security, UI };
}   
