<?php 
namespace practicaUf1; 
use practicaUf1\Partida;
use practicaUf1\Jugador;

session_start();



/*
spl_autoload_register(function ($classe) {
    include $classe . '.php';
                }); */



$_SESSION['fitxer'] = 'jugadors.txt';
?>

<!DOCTYPE html>
<!--
Pràctica UF1: Penjat!! :D
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>JGFdez - Penjat!</title>
    </head>
    <body>
        
        <?php /* A PARTIR DE AQUI ESTO SALE SI NO HAY NADA EN NICK, CORREU, CTNYA Y LLETRA O SI LE DAS A TORNAR */
        if ((!isset($_POST['nick'])
                && !isset($_POST['ctnya'])
                && !isset($_POST['lletra']))  // esta linea del if(#) es para que se quede en la parte del juego al poner las letras
                
                || isset($_POST['tornar'])) // si clicas en "tornar" vuelve al menu principal
            {
            ?>
        
            <h1>Joel G. Fernández - Penjat!</h1><br/><br/>
            <form action="index.php" method="POST">
                Introdueix el teu nick: <input type="text" name="nick" /><br/>
                Introdueix el teu correu: <input type="text" name="correu" /><br/>
                Introdueix la contrasenya: <input type="password" name="ctnya" /><br/>
                Endevina la paraula <input type="hidden" name="paraula" value="MANDONGUILLA" /><br/>
                <input type="submit" value="Jugar! :D" name="entrar"/><input type="reset" value="Esborrar" />
            </form><?php 
        
        } /* A PARTIR DE AQUI EL JUEGO EN SI, O SEA CUANDO HAS PUESTO NICK, CORREU, CTNYA Y VAS PONIENDO LLETRES */
        
        elseif (isset($_POST['entrar'])) {
            
            if (!isset($_SESSION['paraula'])) 
                $_SESSION['paraula'] = $_POST['paraula'];
            
            //comprovaDades($_SESSION['nick'], $_SESSION['correu'], $_SESSION['ctnya']);
            
            if (!isset($_SESSION['nick'])
                    && !isset($_SESSION['ctnya'])
                    && !isset($_SESSION['correu'])
                    ) {
                
                $_SESSION['nick'] = $_POST['nick'];
                $_SESSION['ctnya'] = $_POST['ctnya'];
                $_SESSION['correu'] = $_POST['correu'];
                
            }
            
            $ptda = new Partida($_POST['paraula']);
            $ptda->mostraParaula();
            $ptda->demanaLletra();
            $ptda->mostraIntents();
            
            var_dump($_SESSION);
            echo '<br/>';
            var_dump($_POST);
            
            echo "<br/><form action=\"index.php\" method=\"POST\" />
            . <input type=\"submit\" name=\"tornar\" value=\"Inici\" />
            </form>";
            
        }
        ?>
    </body>
</html>
<?php


/**
 * Description of Partida
 * CLASE PRINCIPAL con ella se crea el objeto partida sobre el q funciona el programa (bueno, no funciona del todo XD)
 * Atributos:
 *      paraula STATIC: palabra oculta q ha de adivinar el jugador
 *      intents: intentos restantes para el jugador
 *      puntuacion: puntos acumulados por aciertos del jugador
 *      jugador: objeto jugador($correu, $nick, $ctnya) que está jugando la partida
 *      lletres: array que guarda las letras que se han introducido por POST
 * 
 * demanaLletres() printa el <form> para introducir letras (o la solución)
 * 
 * mostraParaula() EN CONSTRUCCION printa la palabra secreta (deberia printar guiones bajos pero no está implementado)
 *
 * mostraIntents() NO IPLEMENTADO muestra las imágenes del ahorcado (?)
 * 
 * 261015
 * @author mor
 */
class Partida {
    
    private static $paraula;
    private $intents;
    private $puntuacio;
    private $jugador;
    private $lletres = [];
    
    public function demanaLletra() {
        echo "<form action=\"index.php\" method=\"POST\" />
            Introdueix la paraula o una lletra:<input type=\"text\" name=\"lletra\" />
            <input type=\"submit\" name=\"entrar\" value=\"Enviar lletra\"/>
            </form>"; // ?? ATENCION por alguna razon no lee el POST de lletra D:
    }

    public function mostraParaula() {

        if (isset($_POST['entrar']) 
                    && array_search($_POST['lletra'], $_SESSION['array_lletres']) != FALSE) {
                echo 'correcte lletra<br/>';
                array_push($_SESSION['array_lletres'],$_POST['lletra']);
                $this->lletres[] = $_SESSION['array_lletres'];
        }
        
        var_dump($this->lletres);
        
        echo "Benvingut, ".$this->jugador->getNick().'.<br/><br/><center>PARAULA:</center><br/>';
        echo $this->getParaula();
           

    }
    
    public function mostraIntents() {
        
        return "<aside style='
            width: 300px; 
            height: 300px; 
            background-image: url(/img/ahorcado.gif); 
            background-repeat: no-repeat;'>
            </aside>";
    }
    
    public static function comprovaDades($nick, $correu, $ctnya) {

        if (!isset($correu) ||  // compueba q el nick, correo y contraseña estan puestos
                !isset($nick) ||
                !isset($ctnya))
            {
            exit(0);

        } elseif (strlen($ctnya) < 6) { // comprueba q la contraseña tiene mas de 6 caracteres

            echo "La contrasenya ha de tenir 6 caracters o més.</br>";
            exit(0);

        } else {

            Jugador::llegeixJugadors2();

        }

    }
    
    /*
     *  ** CONSTRUCTORS **
     */
    public function __construct() {
        
        $this->paraula = $_SESSION['paraula'];
        $this->intents = $_SESSION['intents'];
        $this->puntuacio = $_SESSION['puntuacio'];
        $this->lletres = $_SESSION['array_lletres'];
        $this->jugador = new Jugador($_SESSION['correu'],
                $_SESSION['nick'],
                $_SESSION['ctnya']
                );
        
    }
    
    /*
    public function __construct() {
        $this->paraula = array("MANDONGUILLA","RATPENAT","FRENOPATIC","BICICLETA");
        $this->intents = 0;
        $this->puntuacio = 0;
    }
    */
    
    
    
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

/*
 * 
 * Clase para crear objetos Jugador
 * 
 * Atributos: se envían por POST en el formulario inicial
 *      correu -> direccion mail del jugador
 *      nick -> alias del jugador
 *      ctnya -> password del jugador
 *      
 * llegeixJugador1() y llegeixJugador2() son 2 maneras diferentes de leer el
 * archivo 'jugadors.txt' enviado por la variable de sesion $_SESSION['fitxer']
 * posteriormente al avanzar el programa han quedado desactualizadas
 * 
 */

class Jugador {
    
    private $correu;
    private $nick;
    private $ctnya;
    
    public static function llegeixJugadors2() {
        
        $fitxer = $_SESSION['fitxer'];
        
        if (!($f_jugadors = fread(fopen($fitxer,'r'),filesize($fitxer)))) { 

            echo 'Error de comprovació de les dades al servidor.</br>';// si no se puede guardar la lectura del archivo en la variable $jugadors, da error

        } else {
            
            $i = 0; // contador para apuntar a las posiciones de $array_tmp
            $j = 0; // contador para apuntar a los caracteres del string $f_jugadors
            $k = 0; // contador para apuntar a los caracteres de $user_tmp
            $l = FALSE; // boolean para definir si se está copiando el nick o la contraseña
            
            while ($f_jugadors[$j] != NULL) {
                
                while ($f_jugadors[$j] != '\n') { // si la posicion actual de $f_jugadors no es un salto de linea, sigue rulando por el string
                    
                    $user_tmp += $f_jugadors[$j]; // copia caracter a caracter el string $f_jugadors en $user_tmp
                    $j++;
                    
                }
                
                $array_tmp[$i] = $user_tmp; // una vez salga del bucle (encuentra salto de linea) copia el substring obtenido a una posicion del array temporal $array_tmp
                
                while ($user_tmp[$k] != NULL) {
                    
                    if ($user_tmp[$k] == ' ') {  // cuando encuentre un espacio, cambia $l a TRUE para copiar la password a $p
                        
                        $l = TRUE;
                        $k++;
                        
                    }
                    
                    if ($l == FALSE) {
                        
                        $u += $user_tmp[$k];    // $l = FALSE copia a $u para el nick
                        
                    }
                    
                    if ($l == TRUE) {
                        
                        $p += $user_tmp[$k];    // $l = TRUE copia a $p para la password
                        
                    }
                    
                    $k++;
                    
                }
                
                $jugadors[$u] = $p;
                
                $l = FALSE;
                $k = 0;
                
                $i++;    
                
            }
            
           var_dump($jugadors); 
            
        }

    }
    
    public static function llegeixJugadors() { // funcion que retorna un array asociativo ($user => $passwd) a partir del string del fichero donde se guardan los jugadores
        
        $fitxer = $_SESSION['fitxer'];
        
        if (!($f_jugadors = fread(fopen($fitxer,'r'),filesize($fitxer)))) { 

            echo 'Error de comprovació de les dades al servidor.</br>';// si no se puede guardar la lectura del archivo en la variable $jugadors, da error

        } else {

            explode('\n', $f_jugadors);
            var_dump($f_jugadors);

            $jugadors = split('\n',$f_jugadors); // descompone el fichero en un array separado por jugadores

            var_dump($jugadors);

            $i = 0;
            while ($jugadors[$i] != NULL) {

                var_dump($jugadors[$i]);
                
                $jugadors[$i] = explode(" ",$jugadors[$i]);
                echo var_dump($jugadors[$i]).'<br/>';
                
                $i++;

            }
            
            /*
             * 
            $j = 0;

            foreach ($jugadors as $key => $value) {

                foreach ($value as $key => $p) {
                    
                    echo $key." => ".$p.'<br/>';
                    $j++;
                    
                }
                
                $j = 0;
                
            }
             * 
             */
            
            return $jugadors;

        }

    }
    
    /* 
     * ** CONSTRUCTORS **
     */
    
    public function _construct($correu, $nick, $ctnya) {
        echo 'Constructor jugador<br/>';
        $this->correu = $correu;
        $this->nick = $nick;
        $this->ctnya = $ctnya;
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

/*

function llegeixJugadors($fitxer) { // funcion que retorna un array asociativo ($user => $passwd) a partir del string del fichero donde se guardan los jugadores
    
    if (!($f_jugadors = fread(fopen($fitxer,'r'),filesize($fitxer)))) { 
        
        echo 'Error de comprovació de les dades al servidor.</br>';// si no se puede guardar la lectura del archivo en la variable $jugadors, da error
        
    } else {
        
        explode('\n', $f_jugadors);
        var_dump($f_jugadors);
        
        $jugadors = split("\n",$f_jugadors); // descompone el fichero en un array separado por jugadores
        
        var_dump($jugadors);
        
        
        
        $i = 0;
        while ($jugadors[$i] != NULL) {
            
            var_dump($jugadors[$i]);
            $jugadors[$i] = explode(" ",$jugadors[$i]);
            echo var_dump($jugadors[$i]).'hola<br/>';
            $i++;
            
        }
        
        foreach ($jugadors as $key => $value) {
            
            foreach ($value as $u => $p) {
                $jugadors[$u] = $p;
                echo $u." => ".$jugadors[$u].'<br/>';
            }
            
        }
        
        return $jugadors;
    
    }
    
}

function reg_jugador($correu, $n, $c) {
                    
    if((!(strlen($ctnya) < 6) && !ctype_alnum($c) && !ctype_alnum($n))) {

        $jugadors = fopen('jugadors.txt', "r"); // lee el archivo de los jugadores

        foreach ($jugadors as $nick => $ctnya) {

            if($n == $nick && $c == $ctnya) { // nick y ctnya correctos
                return __construct($correu, $nick, $ctnya); //devuelve un objeto jugador
            }

            elseif ($n == $nick && $c != $ctnya) { // nick correcto y ctnya incorrecta
                return FALSE; // devuelve FALSE si la contraseña esta mal
            }

        }

        echo "Correcte.";
        fwrite(fopen('jugadors.txt','a'), $n." ".$c." 0");
            return jugador::class__construct($correu, $n, $c);

    }

    elseif (strlen($ctnya < 6)) {
        ?>
        La contrasenya ha de tenir almenys 6 caracters alfanumèrics<br/>

        <form action="index.php" />
            <input type="submit" value="Tornar" />
        </form>
        <?php
    }
                    
}
*/
?>