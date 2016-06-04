<?php
// $Id: spanish/main.php,v.71 2009/05/15 19:59:00 tcnet Exp $ //
//%%%%%%		Module Name 'WebShow'		%%%%%
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //

defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
* General Terms
*/
define("_WS_ADMIN","Panel de Administración"); //Admin link
define("_WS_CATEGORY","Categoría");
define("_WS_CATSELECT","Seleccione Una Categoría:");
define("_WS_CREATED","Creado: "); 
define("_WS_DIRECTORY","Directorio");
define("_WS_EMBED","Enclavar");
define("_WS_EMBEDLINKJS","Script");
define("_WS_FOOTERTEXT","WebShow medios de comunicación es un directorio de enlaces en los que los miembros presenten enlaces a archivos multimedia, arroyos, podcast webfeeds o sitios para compartir los medios de comunicación.");
define("_WS_HOST","Medios de Comunicación de Acogida");
define("_WS_INTRESTLINK","Programa de web interesante en %s");  // %s is your site name
define("_WS_INTLINKFOUND","Aquí está una programa de web interesante he encontrado en %s");  // %s is your site name
define("_WS_HOME","Página de Inicio");
define("_WS_MODIFY","Modificar");
define("_WS_NEW","Nueva");
define("_WS_NEWTHISWEEK","Nueva Esta Semana");
define("_MS_NOTOWNER","No eres el propietario de esta entrada.");
define("_WS_PAGE","Página");
define("_WS_POPULAR","Popular");
define("_WS_POPUP","Pop-Up");
define("_WS_RANK","Rango");
define("_WS_REQUESTMOD","Solicitud de Modificación de Entrada");
define("_WS_REQUIRED","<b>* = Sección Requerido</b>");
define("_WS_SUBCATEGORY","Subcategoría");
define("_WS_SUBMITLINK","Enviar un enlace");
define("_WS_TOP10","%s 10 Mejores"); // %s is a category title
define("_WS_TOPHITS","Las Mejores Visitas a las Páginas");
define("_WS_TOPVIEWS","Mejores Medios de Comunicación");
define("_WS_UPDATED","Actualizado"); //New Button text
define("_WS_UPTHISWEEK","Actualizado Esta Semana");
define("_WS_VIEWMEDIA","Ver Materiales");

/**
* THERE ARE ...
**/
define("_WS_THEREARE","Cuenta: %s");
define("_WS_THEREAREBY","Tenemos %s espectáculo publicado por %s."); // Used in Playposter view
define("_WS_THEREARECAT","Están %s catagorías en el catálogo.");
define("_WS_THEREARECATNONE","No hay entradas en esta categoría.");
define("_WS_THEREAREINDEX","Nuestro catálogo cuenta con %s medios de comunicación listados en %s categorías."); // Used in index page
define("_WS_THEREARENONE","No hay entradas en el catálogo.");
define("_WS_THEREAREPLAYCAT","Hay %s muestra en la categoría de %s.");  // Play Cat view
define("_WS_THEREARESINGLE","%s es de la categoría de %s en el %s."); //Singlelink.php line 379.  Listing title, category title, sitename modname
define("_WS_THEREARESUBCAT","Hay %s categorías de %s en el directorio.");

/**
* Player
*/
define("_WS_PAUSE","Pausa");
define("_WS_PLAY","Encender");
define("_WS_PLAYER","Reproductor");
define("_WS_PLAYER_ON","Encienda el reproductor");
define("_WS_PLAYER_OFF","Encienda el reproductor apagado");
define("_WS_STOP","Pare");

/**
* Item Info
*/
define("_WS_CREDITS","Créditos");
define("_WS_CREDIT1","Artista");
define("_WS_CREDIT2","Álbum");
define("_WS_CREDIT3","Sello Discográfico");
define("_WS_DESCRIPTION","Descripción");
define("_WS_DOWNLOAD","Descargar");
define("_WS_PAGEHITS","Visitas a las Páginas");
define("_WS_PAGE_VIEW","Ver Página");
define("_WS_POSTER","Presentador: ");
define("_WS_POSTERTITLE","Programa de Web Por %s");
define("_WS_POSTERVIEW","Ver Todo");
define("_WS_POSTERVIEW_DSC","Ver todo de este presentador.");
define("_WS_STATISTICS","Estadísticas");
define("_WS_TITLE","Título");
define("_WS_VIEWS","Observaciónes de Medios");
define("_WS_WEBFEED","Alimentación");
define("_WS_WEBSITE","Sitio");

/*
* BUTTONS
*/
define("_WS_BUTTON_PAGE","Página"); // Page link button text
define("_WS_BUTTON_POPULAR","Popular"); // Popular Button text
define("_WS_BUTTON_NEW","Nueva"); // New Button text
define("_WS_BUTTON_UPDATED","Actualizado"); // New Button text
define("_WS_BUTTON_PLAYERON","Reproductor Encendido");
define("_WS_BUTTON_PLAYEROFF","Reproductor Apagado");

/*
* Item Info Box
*/
define("_WS_CODES","Códigos");
define("_WS_CODES_DSC","Mostrar Insertar Link y Códigos");
define("_WS_CODEBOX","Insertar y Enlace Código");
define("_WS_CODEBOX_DSC","Haga clic dentro de la caja y luego copiar y pegar en su sitio.");
define("_WS_HIDE","Ocultar");
define("_WS_PERMALINK","Perma");
define("_WS_SENDEMAIL","Correo Electrónico a un Amigo");
define("_WS_SHARE","Compartir");
define("_WS_SOCIALBOOKMARK","Marcadores Sociales");

/*
* ID3 TAGS
*/
define("_WS_ID3TAG","ID3 Información de Etiqueta");
define("_WS_ID3TITLE","Títulp");
define("_WS_ID3ARTIST","Artista");
define("_WS_ID3ALBUM","Álbum");
define("_WS_ID3COMPOSER","Compositor");
define("_WS_ID3COPYRIGHT","Los Derechos de Autor");
define("_WS_ID3GENRE","Género");
define("_WS_ID3YEAR","Año");

/*
* Feed and track data
*/

define("_WS_FEED_TEXT","Texto de alimentación");
define("_WS_FEEDDATA","Alimentación de Datos");
define("_WS_TRACKAUDIO","Audio:");
define("_WS_TRACKAUTHOR","Autor:"); 
define("_WS_TRACKCAPTIONS","subtítulo:");
define("_WS_TRACKCAT","Categoría:");
define("_WS_TRACKCITY","Ciudad:");
define("_WS_TRACKDATA","Los Datos del Tema");
define("_WS_TRACKDATE","Día:");
define("_WS_TRACKDESC","Descripción:");
define("_WS_TRACKFILE","Archivo:");
define("_WS_TRACKID","ID:");
define("_WS_TRACKIMAGE","Imagen:");
define("_WS_TRACKLAT","Latitud:");
define("_WS_TRACKLINK","Conexión:");
define("_WS_TRACKLONG","Longitud:");
define("_WS_TRACKSTART","Empazar:"); 
define("_WS_TRACKTITLE","Título:");
define("_WS_TRACKTYPE","Tipo:");

/**
 * Rating
 */
define("_WS_CANTVOTEOWN","No puedes calificar tu propia entrada.");
define("_WS_DONOTVOTE","No vote por su propia entrada.");
define("_WS_ONEVOTE","Un voto");
define("_WS_NORATING","Nada selecciónado.");
define("_WS_NUMVOTES","%s votos");
define("_WS_RATE","Rate");
define("_WS_RATING","Valuación");
define("_WS_RATINGNO","No hay votos");
define("_WS_RATETHISSITE","Califique este programa"); 
define("_WS_RATINGSCALE","Califique 1 (malo) - 10 (bien)");
define("_WS_THANKURATE","Gracias para calificar esta programa.");
define("_WS_TOPRATED","Mejor valorado");
define("_WS_VOTE","Vote");
define("_WS_VOTEONCE","Permite un voto por cada entrada.");
define("_WS_VOTEONCE2","Ya se han evaluado.");

/**
 * Report
 */
define("_WS_ALREADYREPORTED","Un informe se ha enviado.");
define("_WS_FORSECURITY","Por razones de seguridad su nombre de usuario y la dirección IP registrada temporalmente.");
define("_WS_REPORT","Informar");
define("_WS_REPORT_DSC","Por favor, informe programas que están rotas, que abusar nuestros términos de servicio, o mostrar de su propiedad los medios de comunicación.");
define("_WS_REPORTABUSE","Abuso");
define("_WS_REPORTBROKEN","Roto");
define("_WS_REPORTCOMMENT","Comentarío");
define("_WS_REPORTCOMMENT_DSC","Por favor describa su problema.<br />Informes de derecho de autor debe incluir el nombre del propietario y los medios de comunicación de la ubicación original.");
define("_WS_REPORTCOPYRIGHT","Derechos de autor");
define("_WS_THANKSFORINFO","Gracias por la información. Veremos en breve su solicitud.");
define("_WS_THANKSFORHELP","Gracias por ayudarnos a mantener la integridad de este directorio.");

/*
* Response Messages
*/
define("_WS_ALLPENDING","Su información será publicada cuando se verifica.");
define("_WS_CAPTCHA_INCORRECT","Código de Confirmación Incorrecto");
define("_WS_ISAPPROVED","Hemos aprobado su entrada los medios de comunicación la presentación");
define("_WS_MUSTREGFIRST","Lo sentimos, usted no tiene el permiso para hacerlo.<br />Por favor registro o login primero!");
define("_WS_NOTEXIST","No Existe");
define("_WS_NOTALLOWED","Not Permitido");
define("_WS_RECEIVED","Hemos recibido su entrada de medios de comunicación. Gracias!");
define("_WS_REQUESTDENIED","Solicitud Denegada.");
define("_WS_ERROR_EMBEDPLUG","Roto Medios de comunicación: Lo sentimos, este plugin ha incrustar un error.");
define("_WS_ERROR_NOMEDIALOCATION","Broken Media: The media location could not be found.");

/**
* Search
*/

define("_WS_MEDIASEARCH","Búsqueda de medios de comunicación");
define("_WS_SEARCH","Buscar");  // used in search form
define("_WS_SEARCHFOUND","El búsqueda encuentra %s resulto(s) de <i>%s</i>");
define("_WS_SEARCHFOUNDNO","No se encontraron resultados.");
define("_WS_SEARCHRESULTS","Resultados de la búsqueda de %s:"); // %s is search term
define("_WS_SEARCHTERM","Término de búsqueda");
define("_WS_SEARCHTERMENTER","Por favor, introduzca un término de búsqueda.");
define("_WS_SEARCHTERMNO","No hubo término de búsqueda disponibles.");
define("_WS_SEARCHTERMSHORT","La palabra clave de búsqueda debe ser más largo que %s letras.");
define("_WS_SEARCHTERMX","Término no utilizadas");

/**
* Sorting
*/
define("_WS_CATSORTEDBY","%s Categoría por %s"); // Category Pages
define("_WS_CURSORTEDBY","Ordenar por: %s");
define("_WS_MEDIASORTEDBY","Programas de medios de comunicación la lista de la %s"); //Index Page
define("_WS_SORTBY","Ordenar por:");
define("_WS_CATTITLEATOZ","Categoría (A hasta Z)");
define("_WS_CATTITLEZTOA","Categoría (Z hasta A)");
define("_WS_DATEOLD","Día (lo más antiguo)");
define("_WS_DATENEW","Día (lo más nuevo)");
define("_WS_LIDLTOH","ID (bajo)");
define("_WS_LIDHTOL","ID (arriba)");
define("_WS_PAGEHITSLTOM","Visitas a las páginas (lo menos)");
define("_WS_PAGEHITSMTOL","Visitas a las páginas (lo más)");
define("_WS_PARENTLTOH","ID de archivo superior (bajo)");
define("_WS_PARENTHTOL","ID de archivo superior (arriba)");
define("_WS_RATINGLTOH","Clasificación (lo menos)");
define("_WS_RATINGHTOL","Clasificación (lo más)");
define("_WS_TITLEATOZ","Título (A hasta Z)");
define("_WS_TITLEZTOA","Título (Z hasta A)");
define("_WS_VOTESLTOM","Votos (lo menos)");
define("_WS_VOTESMTOL","Votos (lo más)");
define("_WS_VIEWSLTOM","Las observaciónes de los medios de comunicación (lo menos)");
define("_WS_VIEWSMTOL","Las observaciónes de los medios de comunicación (lo más)");
?>