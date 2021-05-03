<?php

class User
{
    /* proprietà della classe */

    /* idUtente dell'account loggato (o NULL se non loggato) */
    private $id;

    /* mail dell'account loggato (o NULL se nessuno loggato) */
    private $mail;

    /* nome dell'utente loggato */
    private $name;

    /* cognome dell'utente loggato */
    private $surname;

    /* scadenza password dell'utente loggato */
    private $expiration;

    /* indirizzo dell'utente loggato */
    private $address;

    /* avatar dell'utente loggato */
    private $avatar;

    /* TRUE se autenticato, FALSE altrimenti */
    private $authenticated;


    /* costruttore */
    public function __construct()
    {
        /* inizializza tutti i parametri a NULL */
        $this->id = NULL;
        $this->mail = NULL;
        $this->name = NULL;
        $this->surname = NULL;
        $this->expiration = NULL;
        $this->address = NULL;
        $this->avatar = NULL;

        /* inizializzo a non autenticato */
        $this->authenticated = FALSE;
    }

    /* costruttore da id*/
    public function popolaDaId(int $id)
    {;
        /* Global pdo */
        global $pdo;

        //query per popolazione
        $query = "SELECT idUser, name, surname, mail, address, avatar
        FROM user
        WHERE idUser = :idUser";

        //array di valori
        $values = array(':idUser' => $id);


        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query
            $res->execute($values);
        } catch (PDOException $e) {
            //in caso di eccezione ritorno l'eccezione
            throw new Exception('Database query error');
        }

        //fetch del risultato
        $res = $res->fetch(PDO::FETCH_ASSOC);

        //se esiste il risultato restituisco il login
        if (is_array($res)) {
            //autenticazione avvenuta con successo, popolo l'oggetto e ritorno true
            $this->id = intval($res['idUser'], 10);
            $this->name = $res['name'];
            $this->surname = $res['surname'];
            $this->mail = $res['mail'];
            $this->address = $res['address'];
            $this->avatar = $res['avatar'];

            return TRUE;
        } else {

            //se non esiste utente
            return FALSE;
        }
    }

    /* Distruttore */
    public function __destruct()
    {
    }

    /********************************************************** */

    //getters 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getExpiration(): ?string
    {
        return $this->expiration;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }



    /********************************************************** */

    /* Funzioni di controllo dati */

    //controlli back-end sui dati inseriti

    /* Controlla nome o cognome */
    public function isNameValid(string $name): bool
    {
        //inizializza valore di ritorno
        $res = TRUE;

        //controllo lunghezza
        $len = mb_strlen($name);

        //lunghezza minima del nome/cognome 1 carattere
        if ($len < 1 || $len > 45) {
            $res = FALSE;
        }

        //ritorno risultato del check
        return $res;
    }

    /* Controlla mail */
    public function isMailValid(string $mail): bool
    {
        //inizializza valore di ritorno
        $res = TRUE;

        //elimino spazi e caratteri superflui
        $mail = trim($mail);

        //controllo lunghezza
        $len = mb_strlen($mail);

        //lunghezza minima della mail 5 caratteri (a@b.c) e max 255
        if ($len < 5 || $len > 255) {
            $res = FALSE;
        }

        //contiente una sola @
        //separa la stringa dove è posta la @, controlla che si creino 2 stringhe
        if (count(explode('@', $mail)) != 2) {
            $res = FALSE;
        }

        //contiene almeno un .
        //separa la stringa dove è posto un punto, controlla che si creino almeno 2 stringhe
        if (count(explode('.', $mail)) < 2) {
            $res = FALSE;
        }

        //ritorno risultato del check
        return $res;
    }


    /* Controlla password */
    public function isPasswordValid(string $pass): bool
    {
        //inizializza valore di ritorno
        $res = TRUE;

        //controllo lunghezza
        $len = mb_strlen($pass);

        //lunghezza minima della password 6 caratteri, massima 15
        if ($len < 6 || $len > 15) {
            $res = FALSE;
        }

        //ritorno risultato del check
        return $res;
    }


    /********************************************************** */

    /* Funzioni di get vari */
    //get dal db da id o mail


    /* ritorna idUtente dall'email, NULL in caso non ci sia nessuna corrispondenza */
    public function getIdFromMail(string $mail): ?int
    {
        /* Global pdo */
        global $pdo;

        // Inizializzo valore di ritorno con NULL (Nessun utente)
        $id = NULL;

        //query di ricerca
        $query = 'SELECT idUser FROM user WHERE mail = :mail';

        //array di valori da passare
        $values = array(':mail' => $mail);

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query
            $res->execute($values);
        } catch (PDOException $e) {
            //in caso di errore restituisco eccezione
            throw new Exception('Database query error');
        }

        //fetch del valore di ritorno
        $row = $res->fetch(PDO::FETCH_ASSOC);

        //se è un array (c'è un valore) ritorno id
        if (is_array($row)) {
            $id = $row['idUser'];
        }

        return $id;
    }


    /********************************************************** */

    /* Funzioni statiche */

    /* aggiunge account e ritorna id */
    public function addAccount(string $mail, string $password, string $name, string $surname, ?string $address): int
    {
        /* Global pdo */
        global $pdo;

        // Controlla se mail è valida o genera eccezione
        if (!$this->isMailValid($mail)) {
            throw new Exception('Mail invalida');
        }

        // Controlla se password è valida o genera eccezione
        if (!$this->isPasswordValid($password)) {
            throw new Exception('Password non valida');
        }

        // Controlla se nome è valido o genera eccezione
        if (!$this->isNameValid($name)) {
            throw new Exception('Nome invalido');
        }

        // Controlla se cognome è valido o genera eccezione
        if (!$this->isNameValid($surname)) {
            throw new Exception('Cognome invalido');
        }

        // Controlla se la mail è già registrata
        if (!is_null($this->getIdFromMail($mail))) {
            throw new Exception('Mail già registrata');
        }


        /* aggiunta dell'account nel db */

        // query di registrazione
        $query = 'INSERT INTO user (name, surname, mail, password, address) VALUES (:name, :surname, :mail, :password, :address)';

        //hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //array di valori 
        $values = array(
            ':name' => $name,
            ':surname' => $surname,
            ':mail' => $mail,
            ':password' => $hash,
            ':address' => $address
        );

        try {

            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        //ritorno ultimo utente aggiunto
        return $pdo->lastInsertId();
    }

    /* elimino l'avatar dell'utente (se esiste) di cui è dato l'id */
    public function removeAvatar(int $idUser)
    {
        /* Global pdo */
        global $pdo;

        //controllo se esiste già un avatar per l'utente e in caso lo elimino
        // query di get avatar
        $query = 'SELECT avatar FROM user WHERE idUser = :id';

        //array di valori 
        $values = array(':id' => $idUser);

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        //fetch
        $res = $res->fetchColumn();

        //controllo se esiste vecchio avatar e lo elimino fisicamente e dal DB
        if ($res != NULL) {

            //elimino avatar se esiste immagine
            if (file_exists('avatars/' . $res)) {
                unlink('avatars/' . $res);
            }

            // query di annullamento avatar
            $query = 'UPDATE user SET avatar = NULL WHERE idUser = :id';

            //array di valori 
            $values = array(':id' => $idUser);

            try {

                //preparo query
                $res = $pdo->prepare($query);

                //eseguo query con passaggio di valori
                $res->execute($values);
            } catch (PDOException $e) {
                throw new Exception('Database query error');
            }
        }
    }

    /* prende il file dell'avatar, fa l'upload e lo inserisce nel db a partire dall'Id utente */
    public function editAvatar(int $idUser, $avatar)
    {
        //controllo back-end della validità del file di avatar
        if ($avatar['size'] > 0) {
            // Controllo che il file non superi i 3 MB
            if ($avatar['size'] > 3145728) {
                throw new Exception("L'avatar non deve superare i 3 MB");
            }

            // Ottengo le informazioni sull'immagine
            list($width, $height, $type, $attr) = getimagesize($avatar['tmp_name']);

            // Controllo che il file sia in uno dei formati GIF, JPG o PNG
            if (($type != 1) && ($type != 2) && ($type != 3)) {
                throw new Exception("L'avatar deve essere un'immagine GIF, JPG o PNG.");
            }
        }

        /* Global pdo */
        global $pdo;

        $this->removeAvatar($idUser);


        //upload nuovo avatar
        //prendo l'estensione del nuovo file
        $ext = pathinfo($avatar['name'], PATHINFO_EXTENSION);

        //creo il nuovo nome del file
        $newName = $idUser . "." . $ext;

        // sposto l'immagine nel percorso avatar
        move_uploaded_file($avatar['tmp_name'], "avatars/" . $newName);


        //procedo all'aggiornamento del DB
        // query di edit avatar
        $query = 'UPDATE user SET avatar = :avatar WHERE idUser = :id';

        //array di valori 
        $values = array(':id' => $idUser, ':avatar' => $newName);

        try {

            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }
    }

    /* edita account da id */
    public function editAccount(int $idUser, string $name, string $surname, string $mail, $optional)
    {
        /* Global pdo */
        global $pdo;

        //controllo validità nome
        if (!$this->isNameValid($name)) {
            throw new Exception('Nome invalido');
        }

        //controllo validità cognome
        if (!$this->isNameValid($surname)) {
            throw new Exception('Cognome invalido');
        }

        //controllo validità mail
        if (!$this->isMailValid($mail)) {
            throw new Exception('E-Mail invalida');
        }


        //controlla se esiste utente già registrato con la stessa mail
        $idFromMail = $this->getIdFromMail($mail);

        if (!is_null($idFromMail) && ($idFromMail != $idUser)) {
            throw new Exception('E-Mail già utilizzata');
        }

        //edito account

        //query base
        $query = 'UPDATE user 
                SET name = :name, 
                    surname = :surname, 
                    mail = :mail';

        //array di valori
        $values = array(
            ':idUser' => $idUser,
            ':name' => $name,
            ':surname' => $surname,
            ':mail' => $mail
        );

        //controllo se vanno aggioranti address e enable (se esistono)
        if (isset($optional['address'])) {
            $query = $query . ', address = :address';

            //controllo se NULL
            if ($optional['address'] == "") {
                $values[':address'] = NULL;
            } else {
                $values[':address'] = $optional['address'];
            }
        }
        if (isset($optional['enabled'])) {
            $query = $query . ', isEnable = :isEnable';
            $values[':isEnable'] = $optional['enabled'];
        }

        //aggiungo ultima parte query
        $query = $query . ' WHERE idUser = :idUser';

        try {

            //preparo query
            $res = $pdo->prepare($query);

            //esecuzione con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }
    }

    /* Elimina un account dato l'id */
    public function deleteAccount(int $id)
    {
        /* Global pdo */
        global $pdo;

        //query per eliminazione dato id
        $query = 'DELETE FROM user WHERE idUser = :idUser';

        //array di valori
        $values = array(':idUser' => $id);

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        //l'eliminazione delle sessioni e degli altri collegamenti avviene grazie ai cascade
    }

    /* cancella mediante una query tutte le sessioni scadute dal db con una semplice query */
    //la funzione viene invocata ogni volta prima di lavorare con le sessioni (registrazione e login)
    //il controllo viene comunque effettuato per ridondanza durante il sessionLogin, qui viene eseguita la pulizia dal DB per completezza
    public function expireSession()
    {

        /* Global pdo */
        global $pdo;

        $query = "DELETE FROM user_session WHERE NOW() >= expiration";

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query
            $res->execute();
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }
    }


    /********************************************************** */

    /* Funzioni sull'utente loggato */


    /* Login con mail e password, ritorna true o false e imposta l'oggetto */
    public function login(string $mail, string $password): bool
    {
        /* Global pdo */
        global $pdo;


        //query per prendere dati dalla mail se account è abilitato, solo id per registrazione della sessione
        $query = 'SELECT idUser, password
            FROM user
            WHERE mail = :mail
                AND isEnabled';

        //array di valori
        $values = array(':mail' => $mail);


        try {
            //preparo la query
            $res = $pdo->prepare($query);

            //eseguo la query
            $res->execute($values);
        } catch (PDOException $e) {
            //in caso di eccezione ritorno l'eccezione 
            throw new Exception('Database query error');
        }

        //fetch del risultato
        $res = $res->fetch(PDO::FETCH_ASSOC);

        //se c'è un risultato procedo verificando la password con password_verify
        if (is_array($res)) {
            if (password_verify($password, $res['password'])) {
                //se l'autenticazione è andata a buon fine popolo gli altri campi utili alla registazione della sessione
                $this->id = intval($res['idUser'], 10);
                $this->mail = $mail;
                $this->authenticated = TRUE;

                //registra la sessione
                $this->registerLoginSession();

                //ritorna autenticazione effettuata con successo
                return TRUE;
            }
        }

        //se non è avvenuta correttamente, si ritorna insuccesso
        return FALSE;
    }


    /* Registrazione della sessione */
    //associa all'id dell'utente il codice di sessione
    private function registerLoginSession()
    {
        //eliminazione sessioni vecchie
        $this->expireSession();

        /* Global pdo */
        global $pdo;

        //controllo se una sessione è già startata
        if (session_status() == PHP_SESSION_ACTIVE) {
            //query per aggiungere la nuova sessione se non esiste, altrimenti faccio replace sul session id già esistente
            //il DBMS con la funzione di default imposta automaticamente l'expiration dopo 24 ore
            $query = 'REPLACE INTO user_session (idSession, idUser) VALUES (:idSession, :idUser)';

            //array di valori
            $values = array(':idSession' => session_id(), ':idUser' => $this->id);


            try {
                //prepara la query
                $res = $pdo->prepare($query);

                //esegue la query
                $res->execute($values);
            } catch (PDOException $e) {
                //in caso di eccezione ritorno l'eccezione 
                throw new Exception('Database query error');
            }
        }
    }

    /* Login con sessione, ritorna True se andato a buon fine o false se fallito */
    public function sessionLogin(): bool
    {
        //eliminazione sessioni vecchie
        $this->expireSession();

        /* Global pdo */
        global $pdo;

        //controlla se sessione è già startata
        if (session_status() == PHP_SESSION_ACTIVE) {
            //query per controllo della sessione nel DB dell'utente abilitato, se non scaduta
            $query = "SELECT user.idUser, user.name, user.surname, user.mail, user.expiration, user.address, user.avatar, user_session.idSession
                FROM user, user_session 
                WHERE user.idUser = user_session.idUser
                    AND idSession = :idSession 
                    AND user_session.expiration > NOW() 
                    AND user.isEnabled";

            //array di valori
            $values = array(':idSession' => session_id());


            try {
                //preparo query
                $res = $pdo->prepare($query);

                //eseguo query
                $res->execute($values);
            } catch (PDOException $e) {
                //in caso di eccezione ritorno l'eccezione
                throw new Exception('Database query error');
            }

            //fetch del risultato
            $res = $res->fetch(PDO::FETCH_ASSOC);

            //se esiste il risultato restituisco il login
            if (is_array($res)) {
                //autenticazione avvenuta con successo, popolo l'oggetto e ritorno true
                $this->id = intval($res['idUser'], 10);
                $this->name = $res['name'];
                $this->surname = $res['surname'];
                $this->mail = $res['mail'];
                $this->expiration = $res['expiration'];
                $this->address = $res['address'];
                $this->avatar = $res['avatar'];
                $this->authenticated = TRUE;

                return TRUE;
            }
        }

        //se non è avvenuta correttamente, si ritorna insuccesso
        return FALSE;
    }

    /* Cambia password dell'account loggato data la vecchia password e la nuova */
    public function changePassword(string $oldPassword, string $newPassword1, string $newPassword2)
    {
        //controllo back-end che le 2 nuove password coincidano
        if ($newPassword1 != $newPassword2) {
            throw new Exception("Le 2 password non corrispondono!");
        }

        //controllo dei requisiti della nuova password
        if (!$this->isPasswordValid($newPassword1)) {
            throw new Exception("La nuova password non &egrave; abbastanza sicura!");
        }

        /* Global pdo */
        global $pdo;

        //query per get della vecchia password (cifrata)
        $query = 'SELECT password FROM user WHERE idUser = :idUser';

        //array di valori
        $values = array(':idUser' => $this->getId());

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        //fetch del risultato
        $res = $res->fetchColumn();

        //controllo coincidenza della vecchia password
        if (!password_verify($oldPassword, $res)) {
            throw new Exception("La vecchia password non &egrave; corretta!");
        }

        //hash della nuova password
        $hash = password_hash($newPassword1, PASSWORD_DEFAULT);


        //inserimento della nuova password
        //query per inserimento nuova password (cifrata) e impostazione nuova data di scadenza
        $query = 'UPDATE user SET password = :password, expiration = DEFAULT WHERE idUser = :idUser';

        //array di valori
        $values = array(':password' => $hash, ':idUser' => $this->getId());

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        //sloggo da tutte le sessioni
        $this->closeAllSessions();

        return true;
    }

    /* Logout dell'utente corrente */
    public function logout()
    {
        /* Global pdo */
        global $pdo;

        //controllo se c'è utente loggato, altrimenti nulla
        if (!$this->authenticated) {
            return;
        }

        //reset di tutti gli attributi
        $this->id = NULL;
        $this->mail = NULL;
        $this->name = NULL;
        $this->surname = NULL;
        $this->expiration = NULL;
        $this->address = NULL;
        $this->avatar = NULL;
        $this->authenticated = FALSE;

        //se ci sono sessioni attive le elimina dal DB
        if (session_status() == PHP_SESSION_ACTIVE) {

            //query per eliminazione della sessione
            $query = 'DELETE FROM user_session WHERE (idSession = :sid)';

            //array di valori 
            $values = array(':sid' => session_id());


            try {
                //preparo query
                $res = $pdo->prepare($query);

                //esecuzione query con passaggio di valori
                $res->execute($values);
            } catch (PDOException $e) {
                //in caso di eccezione ritorno l'eccezione
                throw new Exception('Database query error');
            }
        }
    }

    /* Chiude tutte le sessioni dell'utente corrente */
    public function closeAllSessions()
    {
        /* Global pdo */
        global $pdo;

        //controlla che qualcuno sia loggato
        if (!$this->isAuthenticated()) {
            return;
        }

        //query per eliminazione di tutte le sessioni
        $query = 'DELETE FROM user_session WHERE idUser = :idUser';

        //array di valori
        $values = array(':idUser' => $this->getId());


        try {
            //preparo la query
            $res = $pdo->prepare($query);

            //eseguo la query con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }
    }
}
