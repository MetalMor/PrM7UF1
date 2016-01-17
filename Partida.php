<?php
namespace practicaUf1;
use practicaUf1\Jugador;

/**
 * Description of Partida
 *
 * 261015
 * @author mor
 */
class Partida {
    
    private static $paraula = [];
    private $intents;
    private $puntuacio;
    private $jugador;
    private $lletres = [];
    
    
    
    /*
     *  ** CONSTRUCTORS **
     */
    public function __construct2($paraula) {
        echo 'Constructor2';
        $this->setParaula($paraula);
        $this->intents = 0;
        $this->puntuacio = 0;
    }
    
    public function __construct() {
        $this->paraula = array("MANDONGUILLA","RATPENAT","FRENOPATIC","BICICLETA");
        $this->intents = 0;
        $this->puntuacio = 0;
    }
    
    
    
    /*
     * ** GETTERS **
     */
    
    public function getParaula() {
        return $this->paraula;
    }
    
    public function getPuntuacio() {
        return $this->puntuacio;
    }
    
    public function getIntents() {
        return $this->intents;
    }
    
    
    
    /* 
     * ** SETTERS **
     */
    
    public function setParaula($paraula) {
        $this->paraula = toUpperCase($paraula);
    }

}
