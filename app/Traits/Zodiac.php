<?php
/**
 * Created by PhpStorm.
 * User: papitoff
 * Date: 4/06/19
 * Time: 10:18 AM
 */

namespace App\Traits;


use Carbon\Carbon;

trait Zodiac
{
    public function symbol($date):array
    {
      $dt = Carbon::parse($date);
      $month = $dt->month;
      $day = $dt->day;
      $url = url('/') . '/3546758weartyuio23456789/zodiac/';
      switch ($month) {
          case 1:
              $data = [
                'url' =>  $day > 21 ? 'Acuario.png' : 'Capricornio.png',
                'moment' =>  $day > 21 ? 'Enero 20 – Febrero 18' : 'Diciembre 22 – Enero 19',
                'name' => $day > 21 ? 'Acuario' : 'Capricornio',
                'element' => $day > 21 ? 'Aire' : 'Tierra',
                'quality' => $day > 21 ? 'Fijo' : 'Cardinal',
                'color' => $day > 21 ? 'Azul, Verde-Azul, Gris, Negro' : 'Marrón, Gris, Negro',
                'day' => $day > 21 ? 'Domingo' : 'Sábado',
                'regent' => $day > 21 ? 'Urano' : 'Saturno',
                'compatibility' => $day > 21 ? 'Géminis, Libra' : 'Tauro, Virgo',
                'marriage' => $day > 21 ? 'Leo' : 'Cáncer',
                'numbers' => $day > 21 ? '4, 8, 13, 17, 22, 26' : '1, 4, 8, 10, 13, 17, 19, 22, 26',
              ];
          break;
        case 2:
            $data = [
                'url' =>  $day > 19 ? 'Piscis.png' : 'Capricornio.png',
                'moment' => $day > 19 ? 'Febrero 19 – Marzo 20' : 'Enero 20 – Febrero 18',
                'name' => $day > 19 ? 'Piscis' : 'Capricornio',
                'element' => $day > 19 ? 'Agua' : 'Tierra',
                'quality' => $day > 19 ? 'Mutable' : 'Cardinal',
                'color' => $day > 19 ? 'Mauve, Lila, Púrpura, Violeta, Verde mar' : 'Marrón, Gris, Negro',
                'day' => $day > 19 ? 'Jueves, Lunes' : 'Sábado',
                'regent' => $day > 19 ? 'Neptuno' : 'Saturno',
                'compatibility' => $day > 19 ? 'Cáncer, Escorpio' : 'Tauro, Virgo',
                'marriage' => $day > 19 ? 'Leo' : 'Cáncer',
                'numbers' => $day > 19 ? '3, 7, 12, 16, 21, 25, 30, 34, 43, 52' : '1, 4, 8, 10, 13, 17, 19, 22, 26'
            ];
          break;
        case 3:
            $data = [
                'url' =>  $day > 20 ? 'Aries.png' : 'Piscis.png',
                'moment' => $day > 20 ? 'Marzo 21 – Abril 19' : 'Febrero 19 – Marzo 20',
                'name' => $day > 20 ? 'Aries' : 'Piscis',
                'element' => $day > 20 ? 'Fuego' : 'Agua',
                'quality' => $day > 20 ? 'Radical' : 'Mutable',
                'color' => $day > 20 ? 'Rojo' : 'Mauve, Lila, Púrpura, Violeta, Verde mar',
                'day' => $day > 20 ? 'Martes' : 'Jueves, Lunes',
                'regent' => $day > 20 ? 'Marte' : 'Neptuno',
                'compatibility' => $day > 20 ? 'Sagitario, Leo' : 'Cáncer, Escorpio',
                'marriage' => $day > 20 ? 'Libra' : 'Leo',
                'numbers' => $day > 20 ? '1, 9' : '3, 7, 12, 16, 21, 25, 30, 34, 43, 52'
            ];
          break;
        case 4:
            $data = [
                'url' =>  $day > 20 ? 'Tauro.png' : 'Aries.png',
                'moment' =>  $day > 20 ? 'Abril 20 – Mayo 20' : 'Marzo 21 – Abril 19',
                'name' => $day > 20 ? 'Tauro' : 'Aries',
                'element' => $day > 20 ? 'Tierra' : 'Fuego',
                'quality' => $day > 20 ? 'Fijo' : 'Radical',
                'color' => $day > 20 ? 'Azul, Rosa, Verde' : 'Rojo',
                'day' => $day > 20 ? 'Viernes, Lunes' : 'Martes',
                'regent' => $day > 20 ? 'Venus' : 'Marte',
                'compatibility' => $day > 20 ? 'Virgo, Capricornio' : 'Sagitario, Leo',
                'marriage' => $day > 20 ? 'Escorpio' : 'Libra',
                'numbers' => $day > 20 ? '2, 4, 6, 11, 20, 29, 37, 47, 56' :'1, 9'
            ];
          break;
        case 5:
            $data = [
                'url' =>  $day > 21 ? 'Geminis.png' : 'Tauro.png',
                'moment' =>  $day > 21 ? 'Mayo 21 – Junio 20' : 'Abril 20 – Mayo 20',
                'name' => $day > 21 ? 'Geminis' : 'Tauro',
                'element' => $day > 21 ? 'Aire' : 'Tierra',
                'quality' => $day > 21 ? 'Mutable' : 'Fijo',
                'color' => $day > 21 ? 'Verde, Amarillo' : 'Azul, Rosa, Verde',
                'day' => $day > 21 ? 'Miércoles' : 'Viernes, Lunes',
                'regent' => $day > 21 ? 'Mercurio' : 'Venus',
                'compatibility' => $day > 21 ? 'Libra, Acuario' : 'Virgo, Capricornio',
                'marriage' => $day > 21 ? 'Sagitario' : 'Escorpio',
                'numbers' => $day > 21 ? '3, 8, 12, 23' : '2, 4, 6, 11, 20, 29, 37, 47, 56',
            ];
          break;
        case 6:
            $data = [
                'url' =>  $day > 20 ? 'Cancer.png' : 'Geminis.png',
                'moment' =>  $day > 20 ? 'Junio 21 – Julio 22' : 'Mayo 21 – Junio 20',
                'name' => $day > 20 ?  'Cancer' : 'Geminis',
                'element' => $day > 20 ?  'Agua' : 'Aire',
                'quality' => $day > 20 ? 'Cardinal' : 'Mutable',
                'color' => $day > 20 ?  'Blanco' : 'Verde, Amarillo',
                'day' => $day > 20 ? 'Lunes, Jueves' : 'Miércoles',
                'regent' => $day > 20 ? 'Luna' : 'Mercurio',
                'compatibility' => $day > 20 ? 'Escorpio, Piscis' :'Libra, Acuario',
                'marriage' => $day > 20 ? 'Capricornio' : 'Sagitario',
                'numbers' => $day > 20 ? '2, 7, 11, 16, 20, 25' : '3, 8, 12, 23'
            ];
          break;
        case 7:
            $data = [
                'url' => $day > 22 ? 'Leo.png' : 'Cancer.png',
                'moment' =>  $day > 22 ? 'Julio 23 – Agosto 22' : 'Junio 21 – Julio 22',
                'name' => $day > 22 ?  'Leo' : 'Cancer',
                'element' => $day > 22 ? 'Fuego' : 'Agua',
                'quality' => $day > 22 ? 'Fijo' : 'Cardinal',
                'color' => $day > 22 ? 'Oro, Naranja, Blanco, Rojo' : 'Blanco',
                'day' => $day > 22 ? 'Domingo' : 'Lunes, Jueves',
                'regent' => $day > 22 ? 'Sol' : 'Luna',
                'compatibility' => $day > 22 ? 'Aries, Sagitario' : 'Escorpio, Piscis',
                'marriage' => $day > 22 ? 'Acuario' : 'Capricornio',
                'numbers' => $day > 22 ? '1, 4, 10, 13, 19, 22' : '2, 7, 11, 16, 20, 25',
            ];
          break;
        case 8:
            $data = [
                'url' =>  $day > 21 ? 'Virgo.png' : 'Leo.png',
                'moment' =>  $day > 21 ? 'Agosto 23 – Septiembre 22' :  'Julio 23 – Agosto 22',
                'name' => $day > 21 ?  'Virgo' : 'Leo',
                'element' => $day > 21 ? 'Tierra' : 'Fuego',
                'quality' => $day > 21 ?  'Mutable' : 'Fijo',
                'color' => $day > 21 ? 'Blanco, Amarillo, Beige, Verde Bosque' : 'Oro, Naranja, Blanco, Rojo',
                'day' => $day > 21 ? 'Miércoles' : 'Domingo',
                'regent' => $day > 21 ? 'Mercurio' :  'Sol',
                'compatibility' => $day > 21 ? 'Tauro, Capricornio' :  'Aries, Sagitario',
                'marriage' => $day > 21 ? 'Piscis' : 'Acuario',
                'numbers' => $day > 21 ? '5, 14, 23, 32, 41, 50' : '1, 4, 10, 13, 19, 22'
            ];
          break;
        case 9:
            $data = [
                'url' =>  $day > 22 ? 'Libra.png' : 'Virgo.png',
                'moment' => $day > 22 ? 'Septiembre 23 – Octubre 22' : 'Agosto 23 – Septiembre 22',
                'name' => $day > 22 ?  'Libra' :  'Virgo',
                'element' => $day > 22 ? 'Aire' : 'Tierra',
                'quality' => $day > 22 ? 'Cardinal': 'Mutable',
                'color' => $day > 22 ? 'Azul verde': 'Blanco, Amarillo, Beige, Verde Bosque',
                'day' => $day > 22 ? 'Viernes' : 'Miércoles',
                'regent' => $day > 22 ? 'Venus' : 'Mercurio',
                'compatibility' => $day > 22 ? 'Géminis' : 'Tauro, Capricornio',
                'marriage' => $day > 22 ? 'Aries' : 'Piscis',
                'numbers' => $day > 22 ? '6, 15, 24, 33, 42, 51, 60' : '5, 14, 23, 32, 41, 50'
            ];
          break;
        case 10:
            $data = [
                'url' =>  $day > 22 ? 'Escorpio.png' : 'Libra.png',
                'moment' => $day > 22 ? 'Octubre 23 – Noviembre 21' : 'Septiembre 23 – Octubre 22',
                'name' => $day > 22 ?  'Escorpio' :  'Libra',
                'element' => $day > 22 ? 'Agua' : 'Aire',
                'quality' => $day > 22 ? 'Fijo' : 'Cardinal',
                'color' => $day > 22 ? 'Escarlata, Rojo' : 'Azul verde',
                'day' => $day > 22 ? 'Martes' : 'Viernes',
                'regent' => $day > 22 ? 'Plutón' : 'Venus',
                'compatibility' => $day > 22 ? 'Cáncer, Piscis' : 'Géminis',
                'marriage' => $day > 22 ? 'Tauro' : 'Aries',
                'numbers' => $day > 22 ? '9, 18, 27, 36, 45, 54, 63, 72, 81, 90' : '6, 15, 24, 33, 42, 51, 60'
            ];
          break;
        case 11:
            $data = [
                'url' => $day > 21 ? 'Sagitario.png' : 'Escorpio.png',
                'moment' => $day > 21 ?  'Noviembre 22 - Diciembre 21' : 'Octubre 23 – Noviembre 21',
                'name' => $day > 21 ?  'Sagitario' :  'Escorpio',
                'element' => $day > 21 ? 'Fuego' : 'Agua',
                'quality' => $day > 21 ? 'Mutable' : 'Fijo',
                'color' => $day > 21 ? 'Violeta, Púrpura, Rojo, Rosa' : 'Escarlata, Rojo',
                'day' => $day > 21 ? 'Jueves' : 'Martes',
                'regent' => $day > 21 ? 'Júpiter' : 'Plutón',
                'compatibility' => $day > 21 ? 'Aries, Leo' : 'Cáncer, Piscis',
                'marriage' => $day > 21 ? 'Géminis' : 'Tauro',
                'numbers' => $day > 21 ? '3, 12, 21, 30' :  '9, 18, 27, 36, 45, 54, 63, 72, 81, 90',
            ];
          break;
        case 12:
            $data = [
                'url' =>   $day > 21 ? 'Capricornio.png' : 'Sagitario.png',
                'moment' =>  $day > 21 ? 'Diciembre 22 – Enero 19' :  'Noviembre 22 - Diciembre 21',
                'name' => $day > 21 ?  'Capricornio': 'Sagitario',
                'element' => $day > 21 ? 'Tierra' :  'Fuego',
                'quality' => $day > 21 ? 'Cardinal' : 'Mutable',
                'color' => $day > 21 ? 'Marrón, Gris, Negro' : 'Violeta, Púrpura, Rojo, Rosa',
                'day' => $day > 21 ? 'Sábado' : 'Jueves',
                'regent' => $day > 21 ? 'Saturno' : 'Júpiter',
                'compatibility' => $day > 21 ? 'Tauro, Virgo' : 'Aries, Leo',
                'marriage' => $day > 21 ? 'Cáncer' : 'Géminis',
                'numbers' => $day > 21 ? '1, 4, 8, 10, 13, 17, 19, 22, 26' : '3, 12, 21, 30'
            ];
          break;
        default:
            $data = [];
      }
      $data['url'] = $url . $data['url'];
      return $data;
    }
}
