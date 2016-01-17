<?php
namespace practicaUf1;

/**
 * Description of Jugador
 * 
 * 261015
 * @author mor
 */
class Jugador {
    
    private $correu;
    private $nick;
    private $ctnya;
    private $max_punt;
    
    
    
    /* 
     * ** CONSTRUCTORS **
     */
    
    public static function regJugador() {
        
        if(count_chars($__POST["ctnya"]) < 6 &&
                ctype_alnum($c = $__POST["ctnya"]) &&
                ctype_alnum($n = $__POST["nick"])) {
            
            $jugadors = file('jugadors.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // lee el archivo de los jugadores
    
            foreach ($jugadors as $nick => $ctnya) {
                
                if($n == $nick && $c == $ctnya) { // nick y ctnya correctos
                    return __construct($__POST["correu"], $nick, $ctnya); //devuelve un objeto jugador
                }
                
                elseif ($n == $nick && $c != $ctnya) { // nick correcto y ctnya incorrecta
                    return FALSE; // devuelve FALSE si la contraseña esta mal
                }
                
            }
            
            fwrite(fopen('jugadors.txt','a'), $n." ".$c." 0");
            return __construct($__POST["correu"], $n, $c);
            
        }
        
        else return 1;
        
    }
    
    public function _construct($correu, $nick, $ctnya) {
        $this->correu = $correu;
        $this->nick = $nick;
        $this->ctnya = $ctnya;
        $this->max_punt = 0;
    }
    
    public function _construct2() {
        $this->correu = "cacaculopedopis@derpmail.dog";
        $this->nick = "mor";
        $this->ctnya = "qwertyuiop";
        $this->max_punt = 0;
    }
    
    
    
    /*
     * ** GETTERS **
     */
    
    public function getCorreu() {
        return $this->correu;
    }

    public function getNick() {
        return $this->nick;
    }

    public function getCtnya() {
        return $this->ctnya;
    }

    public function getMax_punt() {
        return $this->max_punt;
    }
    
    
    
    /* 
     * ** SETTERS **
     */

    public function setCorreu($correu) {
        $this->correu = $correu;
    }

    public function setNick($nick) {
        $this->nick = $nick;
    }

    public function setCtnya($ctnya) {
        $this->ctnya = $ctnya;
    }

    public function setMax_punt($max_punt) {
        $this->max_punt = $max_punt;
    }
    
}
