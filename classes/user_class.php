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

    /* isEnabled dell'utente loggato */
    private $isEnabled;

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
        $this->isEnabled = NULL;

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

    public function getIsEnabled(): ?string
    {
        return $this->isEnabled;
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

    /* Funzioni statiche */


    /* aggiunge account e ritorna id */
    public function addAccount(string $mail, string $password, string $name, string $surname, ?string $address, ?string $avatar): int
    {
        /* Global pdo */
        global $pdo;

        // Controlla se mail è valida o genera eccezione
        if (!$this->isMailValid($mail)) {
            throw new Exception('Invalid mail');
        }

        // Controlla se password è valida o genera eccezione
        if (!$this->isPasswordValid($password)) {
            throw new Exception('Invalid password');
        }

        // Controlla se nome è valido o genera eccezione
        if (!$this->isNameValid($name)) {
            throw new Exception('Invalid name');
        }

        // Controlla se cognome è valido o genera eccezione
        if (!$this->isNameValid($surname)) {
            throw new Exception('Invalid surname');
        }
        /*
        // Controlla se la mail è già registrata
        if (!is_null(getIdFromMail($mail))) {
            throw new Exception('Mail already registered');
        }
*/

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
}
