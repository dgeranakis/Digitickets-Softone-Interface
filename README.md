# Steps to start a new Laravel Project

- [ ] Δημιουργία project στο gitlab
    - Κάτω από το group Twinnet, New project -> Create blank project (with default settings)
- [ ] Κατέβασμα τοπικά μέσω VS Code
- [ ] Αντιγραφή αρχείων από το project laravel-11-start
- [ ] Δημιουργία βάσης τοπικά
- [ ] Προσαρμογή .env αρχείου παίρνοντας ως βάση το .env-example και προσαρμόζοντας ό,τι χρειάζεται
- [ ] Αλλαγή των logo στο φάκελο /public/images/logo καθώς και το /public/images/favicon.ico
- [ ] Εκτέλεση των ακόλουθων εντολών
    ```
    composer update
    php artisan key:generate
    php artisan storage:link
    php artisan migrate
    npm install
    npm rebuild
    npm run dev
    ```
- [ ] Προσθήκη admin user στην βάση (password = ddoukas)
    ```
    INSERT INTO `users` (`id`, `name`, `email`, `is_admin`, `active`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`,  `remember_token`, `lines_per_page`, `created_by_user`, `created_at`, `updated_by_user`, `updated_at`) VALUES
    (1, 'ddoukas', 'ddoukas@twinnet.gr', 1, 1, NOW(), '$2y$10$Xb6.9Kb2Ob6Ntd5OKtpasu1VA56IeQE4kUBBAmCpJUQeMaZqqrsCe',	NULL, NULL, NULL, 10, NULL, NOW(), NULL, NOW());
    
    INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES (1, 'App\\Models\\User', 1);
    ```
- [ ] Προσαρμογή του routes/web.php αρχείου εάν θέλουμε η εφαρμογή να έχει διαφορετικό front και όχι μόνο admin.
- [ ] Έλεγχος τοπικά ότι το project παίζει χωρίς πρόβλημα αφού εκτελεστεί η εντολή:
    ```
    php artisan serve
    ```
- [ ] Πρώτο commit στο gitlab εφόσον όλα είναι οκ (προσοχή στα .gitignore αρχεία).


## Ανέβασμα στην pair

- [ ] Δημιουργία root φακέλου μέσω ftp κάτω από το path: /usr/www/users/ddoukas/eshops/
- [ ] Δημιουργία subdomain μέσω του admin της pair [my.pair.com](https://my.pair.com/)
    - Domains -> Add a Domain Name
    - Επιλογή 'Add subdomain', subdomain name: s-tickets.pairsite.com
    - Select Hosting Type: Shared IP Domain
- [ ] Επεξεργασία subdomain μέσω του admin της pair
    - Domains -> Manage Your Domain Names -> επιλογή του domain που θέλουμε
    - Change Domain Mapping ώστε να βλέπει κάτω από τον public φάκελο του project άρα συμπληρώνουμε το    eshops/root-dir/public/
    - Change cgi-bin Mapping ώστε να βλέπει κάτω από τον public/cgi-bin φάκελο του project άρα συμπληρώνουμε το    eshops/root-dir/public/cgi-bin/
- [ ] Διαγραφή αυτόματα δημιουργημένου φακέλου μέσω ftp
    - Στο path /usr/www/users/ddoukas/ θα έχει δημιουργηθεί ένας φάκελος με την ονομασία του subdomain τον οποίο διαγράφουμε
- [ ] Δημιουργία βάσης μέσω του admin της pair
    - Databases -> Create a new database 
    - Επιλογή ονόματος (δεν δέχεται κάτω παύλες, άρα γράφουμε σε camel case) 
    - Access Level: local only
    - optimization period: monthly
    - Την σελίδα με τα details που εμφανίζεται την αντιγράφουμε και την αποθηκεύουμε στον φάκελο του project στον τοπικό server σε ένα txt αρχείο (όλα τα credentials)
    - Αυτά που χρειαζόμαστε για το project είναι τα full access.
- [ ] Ανέβασμα αρχείων του project μέσω ftp στον root φάκελο που δημιουργήσαμε
    - Δεν χρειάζονται το root/public/storage (δημιουργείται μετά με εντολή ως link)
    - Δεν χρειάζεται το περιεχόμενο του φάκελου vendor (δημιουργείται μετά)
    - Δεν χρειάζεται το περιεχόμενο των υποφακέλων κάτω από το root/storage/framework & root/storage/logs (Εκτός από τα .gitignore αρχεία)
    - Ανεβάζουμε zip αρχείο με το περιεχόμενο στον root φάκελο και κάνουμε unzip μέσω ssh με την εντολη: 
        ```
        unzip -q name.zip
        ```
- [ ] Permissions φακέλων
    - O public φάκελος και όλοι οι υποφάκελοί του (recursively) θα πρέπει να έχουν permissions 777
    - O storage φάκελος και όλοι οι υποφάκελοί του (recursively) θα πρέπει να έχουν permissions 777
- [ ] Edit το .env αρχείο
    - APP_ENV=production
    - APP_DEBUG=false
    - LOG_CHANNEL=daily
    - νέα στοιχεία βάσης
    - νέα στοιχεία smtp (εάν χρειάζονται)
- [ ] Ανέβασμα βάσης
    - Παίρνουμε backup σε .sql αρχείο την τοπική μας βάση
    - Ανεβάζουμε το backup μέσω του [epartners.gr/super/adminer.php](http://epartners.gr/super/adminer.php)  στην νέα μας βάση (συνδεόμαστε με τα στοιχεία που έχουμε κρατήσει πιο πριν)
    - Σε περίπτωση που το .sql αρχείο είναι πολύ μεγάλο και δεν ανεβαίνει μέσω του adminer το ανεβάζουμε με ftp στον server και στην συνέχεια μέσω ssh τρέχουμε την ακόλουθη εντολή:
        ```
        mysql -u[username] -p[password] --default-character-set=utf8 [database_name] < path_of_file_name.sql 
        ```
- [ ] Εγκατάσταση cgi-bin
    - Δημιουργία cgi-bin φακέλου κάτω από τον public φάκελο του project μέσω ftp (Permissions: 755)
    - Μέσω ssh κάνουμε copy το κατάλληλο αρχείο cgi στον προηγούμενο φάκελο με την ακόλουθη εντολή: (περισσότερες πληροφορίες στο [www.pair.com/support/kb/php-as-cgi](https://www.pair.com/support/kb/php-as-cgi/) ):
        ```
        cp /usr/www/cgi-bin/php82.cgi /usr/www/users/ddoukas/eshops/root-dir/public/cgi-bin/
        ```
    -	Στο αρχείο /usr/www/users/ddoukas/eshops/root-dir/public/.htaccess προσθέτουμε στην αρχή τις ακόλουθες δυο γραμμές
        ```
        AddType application/x-httpd-php82 .php
        Action application/x-httpd-php82 /cgi-bin/php82.cgi
        ```
- [ ] Μέσω ssh συνδεόμαστε στην pair κάτω από τον root φάκελο του Project τρέχουμε τις ακόλουθες εντολές:
    ```
    composer update
    php artisan key:generate
    php artisan storage:link
    npm install
    npm rebuild
    npm run prod
    ```
- [ ] Ελέγχουμε ότι όλα παίζουν σωστά (θα πρέπει να έχουν περάσει τουλάχιστον 10 λεπτά μετά το 8.c.) πατώντας το νέο subdomain στον browser
    - Σε περίπτωση error ελέγχουμε τα Log αρχεία (root/storage/logs). Εάν δεν έχει καταγραφεί κάτι στα Log αρχεία, ενεργοποιούμε το debug μέσω του .env αρχείου και εκτελούμε για σιγουριά και την ακόλουθη εντολή μέσω ssh
        ```
        php artisan cache:clear
        ```
