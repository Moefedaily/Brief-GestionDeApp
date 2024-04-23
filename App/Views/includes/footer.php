</main>
    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <p>&copy; <?php echo date('Y'); ?> Gestion De Apprenants</p>
            <?php if ($loggedIn): ?>
                <p>Welcome, <?php echo $userName; ?></p>
            <?php endif; ?>
        </div>
    </footer>
</body>
</html>