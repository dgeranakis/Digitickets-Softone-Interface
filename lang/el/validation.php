<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Το πεδίο :attribute πρέπει να γίνει αποδεκτό.',
    'accepted_if' => 'Το πεδίο :attribute πρέπει να γίνει αποδεκτό όταν το πεδίο :other είναι :value.',
    'active_url' => 'Το πεδίο :attribute δεν είναι έγκυρη διεύθυνση URL.',
    'after' => 'Το πεδίο :attribute πρέπει να είναι ημερομηνία μετά τις :date.',
    'after_or_equal' => 'Το πεδίο :attribute πρέπει να είναι ημερομηνία ίση ή μετά από τις :date.',
    'alpha' => 'Το πεδίο :attribute μπορεί να περιέχει μόνο γράμματα.',
    'alpha_dash' => 'Το πεδίο :attribute μπορεί να περιέχει μόνο γράμματα, αριθμούς, παύλες και κάτω παύλες.',
    'alpha_num' => 'Το πεδίο :attribute μπορεί να περιέχει μόνο γράμματα και αριθμούς.',
    'array' => 'Το πεδίο :attribute πρέπει να είναι πίνακας.',
    'ascii' => 'Το πεδίο :attribute πρέπει να περιέχει μόνο αλφαριθμητικούς χαρακτήρες και σύμβολα ενός byte.',
    'before' => 'Το πεδίο :attribute πρέπει να είναι ημερομηνία πριν από τις :date.',
    'before_or_equal' => 'Το πεδίο :attribute πρέπει να είναι ημερομηνία ίση ή πριν από τις :date.',
    'between' => [
        'array' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min - :max αντικειμένων.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min - :max kilobytes.',
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min - :max.',
        'string' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min - :max χαρακτήρων.',
    ],
    'boolean' => 'Το πεδίο :attribute πρέπει να είναι αληθές ή ψευδές.',
    'can' => 'Το πεδίο :attribute περιέχει μια μη εξουσιοδοτημένη τιμή.',
    'confirmed' => 'Η επιβεβαίωση του :attribute δεν ταιριάζει.',
    'contains' => 'Από το πεδίο :attribute λείπει προαπαιτούμενη τιμή.',
    'current_password' => 'Το συνθηματικό είναι λανθασμένο.',
    'date' => 'Το πεδίο :attribute δεν είναι έγκυρη ημερομηνία.',
    'date_equals' => 'Το πεδίο :attribute πρέπει να είναι μια ημερομηνία ίση με :date.',
    'date_format' => 'Το πεδίο :attribute δεν είναι της μορφής :format.',
    'decimal' => 'Το πεδίο :attribute πρέπει να έχει :decimal ​​δεκαδικά ψηφία.',
    'declined' => 'Το πεδίο :attribute πρέπει να απορριφθεί.',
    'declined_if' => 'Το πεδίο :attribute πρέπει να απορριφθεί όταν το πεδίο :other είναι :value.',
    'different' => 'Τα πεδία :attribute και :other πρέπει να είναι διαφορετικά.',
    'digits' => 'Το πεδίο :attribute πρέπει να είναι :digits ψηφία.',
    'digits_between' => 'Το πεδίο :attribute πρέπει να είναι μεταξύ :min και :max ψηφίων.',
    'dimensions' => 'Το πεδίο :attribute περιέχει μη έγκυρες διαστάσεις εικόνας.',
    'distinct' => 'Το πεδίο :attribute περιέχει μια διπλότυπη τιμή.',
    'doesnt_end_with' => 'Το πεδίο :attribute δεν μπορεί να τελειώνει με ένα από τα ακόλουθα: :values.',
    'doesnt_start_with' => 'Το πεδίο :attribute δεν μπορεί να ξεκινά με ένα από τα ακόλουθα: :values.',
    'email' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη διεύθυνση email.',
    'ends_with' => 'Το πεδίο :attribute πρέπει να τελειώνει με ένα από τα ακόλουθα: :values.',
    'enum' => 'Το επιλεγμένο πεδίο :attribute δεν είναι έγκυρο.',
    'exists' => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'extensions' => 'Το πεδίο :attribute πρέπει να έχει μία από τις ακόλουθες επεκτάσεις: :values.',
    'file' => 'Το πεδίο :attribute πρέπει να είναι αρχείο.',
    'filled' => 'To πεδίο :attribute πρέπει να έχει τιμή.',
    'gt' => [
        'array' => 'To πεδίο :attribute πρέπει να έχει περισσότερα από :value αντικείμενα.',
        'file' => 'To πεδίο :attribute πρέπει να είναι μεγαλύτερο από :value kilobytes.',
        'numeric' => 'To πεδίο :attribute πρέπει να είναι μεγαλύτερο από :value.',
        'string' => 'To πεδίο :attribute πρέπει να είναι μεγαλύτερο από :value χαρακτήρες.',
    ],
    'gte' => [
        'array' => 'To πεδίο :attribute πρέπει να έχει :value αντικείμενα ή περισσότερα.',
        'file' => 'To πεδίο :attribute πρέπει να είναι μεγαλύτερο ή ίσο με :value kilobytes.',
        'numeric' => 'To πεδίο :attribute πρέπει να είναι μεγαλύτερο ή ίσο με :value.',
        'string' => 'To πεδίο :attribute πρέπει να είναι μεγαλύτερο ή ίσο με :value χαρακτήρες.',
    ],
    'hex_color' => 'Το πεδίο :attribute πρέπει να είναι έγκυρο δεκαεξαδικό χρώμα.',
    'image' => 'Το πεδίο :attribute πρέπει να είναι εικόνα.',
    'in' => 'Το επιλεγμένο πεδίο :attribute δεν είναι έγκυρο.',
    'in_array' => 'Το πεδίο :attribute δεν περιέχεται στο πεδίο :other.',
    'integer' => 'Το πεδίο :attribute πρέπει να είναι ακέραιος αριθμός.',
    'ip' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη διεύθυνση IP.',
    'ipv4' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη διεύθυνση IPv4.',
    'ipv6' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη διεύθυνση IPv6.',
    'json' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη συμβολοσειρά JSON.',
    'list' => 'Το πεδίο :attribute πρέπει να είναι λίστα.',
    'lowercase' => 'Το πεδίο :attribute πρέπει να είναι πεζό.',
    'lt' => [
        'array' => 'To πεδίο :attribute πρέπει να έχει λιγότερα από :value αντικείμενα.',
        'file' => 'To πεδίο :attribute πρέπει να είναι μικρότερo από :value kilobytes.',
        'numeric' => 'To πεδίο :attribute πρέπει να είναι μικρότερo από :value.',
        'string' => 'To πεδίο :attribute πρέπει να είναι μικρότερo από :value χαρακτήρες.',
    ],
    'lte' => [
        'array' => 'To πεδίο :attribute δεν πρέπει να υπερβαίνει τα :value αντικείμενα.',
        'file' => 'To πεδίο :attribute πρέπει να είναι μικρότερo ή ίσο με :value kilobytes.',
        'numeric' => 'To πεδίο :attribute πρέπει να είναι μικρότερo ή ίσο με :value.',
        'string' => 'To πεδίο :attribute πρέπει να είναι μικρότερo ή ίσο με :value χαρακτήρες.',
    ],
    'mac_address' => 'To πεδίο :attribute πρέπει να είναι μια έγκυρη διεύθυνση MAC.',
    'max' => [
        'array' => 'Το πεδίο :attribute δεν μπορεί να περιέχει περισσότερα από :max αντικείμενα.',
        'file' => 'Το πεδίο :attribute δεν μπορεί να περιέχει μεγαλύτερό :max kilobytes.',
        'numeric' => 'Το πεδίο :attribute δεν μπορεί να περιέχει μεγαλύτερο από :max.',
        'string' => 'Το πεδίο :attribute δεν μπορεί να περιέχει περισσότερους από :max χαρακτήρες.',
    ],
    'max_digits' => 'Το πεδίο :attribute δεν πρέπει να έχει περισσότερα από :max ψηφία.',
    'mimes' => 'Το πεδίο :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'mimetypes' => 'Το πεδίο :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'min' => [
        'array' => 'Το πεδίο :attribute πρέπει να έχει τουλάχιστον :min αντικείμενα.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι τουλάχιστον :min kilobytes.',
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι τουλάχιστον :min.',
        'string' => 'Το πεδίο :attribute πρέπει να έχει τουλάχιστον :min χαρακτήρες.',
    ],
    'min_digits' => 'Το πεδίο :attribute πρέπει να έχει τουλάχιστον :min ψηφία.',
    'missing' => 'Το πεδίο :attribute πρέπει να λείπει.',
    'missing_if' => 'Το πεδίο :attribute πρέπει να λείπει όταν το :other είναι :value.',
    'missing_unless' => 'Το πεδίο :attribute πρέπει να λείπει εκτός αν το :other είναι :value."',
    'missing_with' => 'Το πεδίο :attribute πρέπει να λείπει όταν υπάρχουν :values.',
    'missing_with_all' => 'Το πεδίο :attribute πρέπει να λείπει όταν υπάρχουν :values.',
    'multiple_of' => 'Το :attribute πρέπει να είναι πολλαπλάσιο του :value',
    'not_in' => 'Το επιλεγμένο :attribute δεν είναι αποδεκτό.',
    'not_regex' => 'Η μορφή του πεδίου :attribute δεν είναι αποδεκτή.',
    'numeric' => 'Το πεδίο :attribute πρέπει να είναι αριθμός.',
    'password' => [
        'letters' => 'Το πεδίο :attribute πρέπει να περιέχει τουλάχιστον ένα γράμμα.',
        'mixed' => 'Το πεδίο :attribute πρέπει να περιέχει τουλάχιστον ένα κεφαλαίο και ένα πεζό γράμμα.',
        'numbers' => 'Το πεδίο :attribute πρέπει να περιέχει τουλάχιστον έναν αριθμό.',
        'symbols' => 'Το πεδίο :attribute πρέπει να περιέχει τουλάχιστον ένα σύμβολο.',
        'uncompromised' => 'Το δεδομένο :attribute εμφανίστηκε σε μια διαρροή δεδομένων. Επιλέξτε ένα διαφορετικό :attribute.',
    ],
    'present' => 'Το πεδίο :attribute πρέπει να υπάρχει.',
    'present_if' => 'Το πεδίο :attribute πρέπει να υπάρχει όταν το :other είναι :value.',
    'present_unless' => 'Το πεδίο :attribute πρέπει να υπάρχει εκτός εάν το :other είναι :value.',
    'present_with' => 'Το πεδίο :attribute πρέπει να υπάρχει όταν υπάρχει :values.',
    'present_with_all' => 'Το πεδίο :attribute πρέπει να υπάρχει όταν υπάρχουν :values.',
    'prohibited' => 'Το πεδίο :attribute απαγορεύεται.',
    'prohibited_if' => 'Το πεδίο :attribute απαγορεύεται όταν το πεδίο :other είναι :value.',
    'prohibited_unless' => 'Το πεδίο :attribute απαγορεύεται εκτός αν το πεδίο :other βρίσκεται στο :values.',
    'prohibits' => 'Το πεδίο :attribute απαγορεύει την παρουσία του πεδίου :other.',
    'regex' => 'Η μορφή του πεδίου :attribute δεν είναι αποδεκτή.',
    'required' => 'Το πεδίο :attribute είναι υποχρεωτικό.',
    'required_array_keys' => 'Το πεδίο  :attribute πρέπει να περιέχει καταχωρήσεις για: :values.',
    'required_if' => 'Το πεδίο :attribute είναι υποχρεωτικό όταν το πεδίο :other είναι :value.',
    'required_if_accepted' => 'Το πεδίο :attribute απαιτείται όταν γίνει αποδεκτό το :other.',
    'required_if_declined' => 'Το πεδίο :attribute είναι απαραίτητο όταν δεν γίνει αποδεκτό το πεδίο :other.',
    'required_unless' => 'Το πεδίο :attribute είναι υποχρεωτικό εκτός αν το πεδίο :other εμπεριέχει :values.',
    'required_with' => 'Το πεδίο :attribute είναι υποχρεωτικό όταν υπάρχει :values.',
    'required_with_all' => 'Το πεδίο :attribute είναι υποχρεωτικό όταν υπάρχουν :values.',
    'required_without' => 'Το πεδίο :attribute είναι υποχρεωτικό όταν δεν υπάρχει :values.',
    'required_without_all' => 'Το πεδίο :attribute είναι υποχρεωτικό όταν δεν υπάρχει κανένα από :values.',
    'same' => 'Τα πεδία :attribute και :other πρέπει να είναι ίδια.',
    'size' => [
        'array' => 'Το πεδίο :attribute πρέπει να περιέχει :size αντικείμενα.',
        'file' => 'Το πεδίο :attribute πρέπει να είναι :size kilobytes.',
        'numeric' => 'Το πεδίο :attribute πρέπει να είναι :size.',
        'string' => 'Το πεδίο :attribute πρέπει να είναι :size χαρακτήρες.',
    ],
    'starts_with' => 'Το πεδίο :attribute πρέπει να ξεκινάει με ένα από τα ακόλουθα: :values',
    'string' => 'Το πεδίο :attribute πρέπει να είναι αλφαριθμητικό.',
    'timezone' => 'Το πεδίο :attribute πρέπει να είναι μια έγκυρη ζώνη ώρας.',
    'unique' => 'Το πεδίο :attribute υπάρχει ήδη.',
    'uploaded' => 'Η μεταφόρτωση του πεδίου :attribute απέτυχε.',
    'uppercase' => 'Το πεδίο :attribute πρέπει να είναι κεφαλαίο.',
    'url' => 'Το πεδίο :attribute δεν είναι έγκυρη διεύθυνση URL.',
    'ulid' => 'Το πεδίο :attribute πρέπει να είναι έγκυρο ULID.',
    'uuid' => 'Το πεδίο :attribute δεν είναι έγκυρο UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'password' => 'Κωδικός Πρόσβασης',
    ],

];
