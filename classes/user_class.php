<?php

require_once("assets/db.php");

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
        if ($len < 1) {
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

        //lunghezza minima della mail 5 caratteri (a@b.c)
        if ($len < 5) {
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
    public function addAccount(string $mail, string $password, string $name, string $surname, ?string $address, ?string $avatar): int
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
        $query = 'INSERT INTO user (name, surname, mail, password, address, avatar) VALUES (:name, :surname, :mail, :password, :address, :avatar)';

        //hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //array di valori 
        $values = array(
            ':name' => $name,
            ':surname' => $surname,
            ':mail' => $mail,
            ':password' => $hash,
            ':address' => $address,
            ':avatar' => $avatar,
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
                var_dump($e);
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
}