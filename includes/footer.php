</main> <!-- Fermeture du contenu principal ouvert dans header.php -->

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-grid">
            <div class="footer-section">
                <h4 class="footer-title glitch" data-text="NAVIGATION">NAVIGATION</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="javascript:void(0)" class="js-reload">Refresh Content</a></li>
                    <?php if(Admin::isAdmin()): ?>
                        <li><a href="admin/dashboard.php">Admin Panel</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="footer-section">
                <h4 class="footer-title glitch" data-text="NETWORK">NETWORK</h4>
                <div class="network-status">
                    <span class="status-indicator online"></span>
                    <span>All systems operational</span>
                </div>
                <div class="crypto-address">
                    <span>BTC: 1A2b3C4d...XyZ</span>
                    <button class="copy-btn" data-crypto="btc">COPY</button>
                </div>
            </div>

            <div class="footer-section">
                <h4 class="footer-title glitch" data-text="SECURITY">SECURITY</h4>
                <div class="security-info">
                    <span>Your IP: <?= $_SERVER['REMOTE_ADDR'] ?></span>
                    <span>Session: <?= substr(session_id(), 0, 8) ?>...</span>
                    <span>Protocol: AES-256</span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="disclaimer">
                <p>WARNING: This is an uncensored platform. We do not log user activity or store identifiable information.</p>
            </div>
            
            <div class="copyright">
                <p>707Forum v0.7.07 | Dark Web Edition | <?= date('Y') ?></p>
                <p class="tor-notice">For maximum security, access this site via Tor network</p>
            </div>
        </div>
    </div>

    
    <script>
  
    document.addEventListener('DOMContentLoaded', function() {
      
        window.addEventListener('devtoolschange', function(e) {
            if(e.detail.open) {
                fetch('/security.php?action=devtools_opened');
            }
        });

        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const cryptoType = this.getAttribute('data-crypto');
                navigator.clipboard.writeText(this.previousElementSibling.textContent);
                this.textContent = 'COPIED';
                setTimeout(() => this.textContent = 'COPY', 2000);
            });
        });

        document.querySelector('.js-reload').addEventListener('click', function() {
            fetch('/api/posts/latest')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.posts-list').innerHTML = '';
                    
                });
        });
    });

    function detectDevTools() {
        const devtools = /./;
        devtools.toString = function() {
            fetch('/security.php?action=devtools_detected');
            return '';
        };
        console.log('%c', devtools);
    }
    setInterval(detectDevTools, 1000);
    </script>
</footer>

<script src="/scripts/main.js"></script>
</body>
</html>
