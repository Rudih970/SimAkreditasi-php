
            </main>

            <!-- Footer -->
            <footer class="border-t border-surface-200 px-4 lg:px-8 py-4">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-surface-500">
                    <p>&copy; <?= date('Y') ?> <?= e(APP_NAME) ?> — <?= e(APP_FULL_NAME) ?></p>
                    <p>Versi <?= e(APP_VERSION) ?></p>
                </div>
            </footer>
        </div><!-- /.flex-1 -->
    </div><!-- /#app -->

    <!-- Search Modal -->
    <div id="search-modal" class="hidden fixed inset-0 z-[60] flex items-start justify-center pt-[15vh]">
        <div id="search-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-xl mx-4 animate-scale-in">
            <div class="bg-white border border-surface-200 rounded-2xl shadow-xl shadow-surface-200/50 overflow-hidden">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-surface-200">
                    <i data-lucide="search" class="w-5 h-5 text-surface-400 flex-shrink-0"></i>
                    <input id="search-input" type="text" placeholder="Cari menu, halaman, atau data..."
                           class="flex-1 bg-transparent text-surface-800 placeholder-surface-400 text-sm outline-none">
                    <kbd class="hidden sm:inline-flex px-2 py-1 bg-surface-100 text-surface-500 text-[10px] font-mono font-medium rounded-md border border-surface-200">ESC</kbd>
                </div>
                <div id="search-results" class="max-h-72 overflow-y-auto p-2">
                    <p class="text-center text-surface-500 text-sm py-8">Ketik untuk mulai mencari...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
    </script>

    <!-- Custom JavaScript -->
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
