<?php

$lang['Version']     = 'Versi&oacute;n';
$lang['Description'] = 'Descripci&oacute;n';
$lang['changelog']   = array(

'2.10.3' => ' 06/07/2012
-[Mejoras]
- Mejorado el paquete de idioma en ingl&eacute;s, se tradujeron varios textos faltantes (By cyberghoser1).-
- Varias mejoras internas de c&oacute;digo.-

-[Bugs]
#10056: Corregido un bug que mostraba la producci&oacute;n en rojo a pesar de tener capacidad para producir.-
#10050: Corregido un bug que permit&iacute;a utilizar el mercader incluso cuando los almacenes estaban llenos (By zebulonbof).-
#10046: Corregido un bug que no permit&iacute;a enviar expediciones.-
#10039: Corregido un bug que provocaba sobre carga (By jstar).-
#10035: Corregido un bug que permit&iacute;a actualizar el juego bajo cualquier circunstancia (By Razican).-
#10034: Corregido un bug en el que no se pod&iacute;a leer la licencia (By Razican).-
#10033: Corregido un bug que no guardaba el id del usuario al registrar un cambio en la p&aacute;gina de configuraciones (By Razican).-
#10032: Corregido un bug que no informaba cuando hab&iacute;a una nueva versi&oacute;n disponible (By Razican).-
#10031: Corregido un bug en la importaci&oacute;n (By Razican).-
#10025: Corregido un bug que mostraba mal los caracteres en la cola de flota y defensas (By jtsamper) .-
#10004: Corregida la formula del calculo de las expediciones (By jstar).-
',

'2.10.2' => ' 06/05/2012
-[Mejoras]
- Mejorado el paquete de idioma en ingl&eacute;s, se tradujeron varios textos faltantes (By cyberghoser1).-
- Mejorado el sistema para determinar si un jugador es fuerte o d&eacute;bil.-
- Mejorado el sistema de producci&oacute;n.-
- El a&ntilde;o final del copyright se muestra din&aacute;micamente en el index.-
- Mejoras internas globales.-
- Mejoras en la validaci&oacute;n del modo vacaciones. Antes realizaba una query + una query por cada planeta, ahora realiza s&oacute;lo una.-
- Mejorada la velocidad de procesamiento al determinar si un usuario puede o no entrar en modo vacaciones.- 
- De 2 a 1 query para determinar si un jugador puede o no entrar en modo vacaciones.-

-[Bugs]
#9854: Corregido un bug que no permit&iacute;a enviar sondas desde galaxia.-
#0000: Corregido un bug en el que el sistema de jugadores d&eacute;biles no funcionaba correctamente al enviar flotas desde galaxia.-
#0000: Corregido un bug que mostraba mal el texto de error de enviar una sonda desde galaxia.-
#0000: Corregido un bug que al cliquear en el nombre de un oficial de redirig&iacute;a a una p&aacute;gina vac&iacute;a.-

- [Cambios]
- Eliminada plantilla "/infos/info_officiers_general.php".-
- Opci&oacute;n de overflow de los almacenes removida.-
- Modificado el link en el admin cp que redirig&iacute;a al tracker.-

-[Seguridad]
- Bloqueada la lectura de los archivos .xml (By Neko).-
',

'2.10.1' => ' 06/04/2012
-[Mejoras]
- Mejora menor en el informe de actualizaci&oacute;n que te informa de que versi&oacute;n a cual otra pasaste.-

-[Bugs]

#9943: Corregido el bug de los permisos de la alianza.-
#9934: Ahora es posible enviar naves con la misi&oacute;n de mantener posici&oacute;n a una Luna.-
#9903: Bug al cambiar los permisos de administradores/moderadores/operadores en el panel administrativo (By PowerMaster).-
#9913: Varios problemas con el phalanx (By jstar).-
#9864: Problemas con los atajos.-
#9857: Error que produc&iacute;a problemas en las estad&iacute;sticas y en varias otras p&aacute;ginas del juego (By Jstar).-
#9852: Calculo erroneo en los d&iacute;as de inactividad de un usuario en el panel administrativo.-
#9850: No se pueden enviar sondas a atacar, producen un error.-
#9837: L&iacute;mite en la cantidad de naves que se pueden enviar durante el salto cu&aacute;ntico (By Gmir17).-
#0000: Corregido un bug menor en el actualizador.-
#0000: Corregido un bug que no mostraba la imagen de eliminar rango en el panel administrativo.-
#0000: Correcciones a caracteres err&oacute;neos en algunos textos.-
#0000: Corregido un bug que imped&iacute;a enviar una flota a mantener posici&oacute;n si no ten&iacute;a deposito de la alianza.-
#0000: Corregido un bug que no informaba correctamente si hab&iacute;a una nueva versi&oacute;n disponible.-
#0000: Corregido un bug que mostraba &uacute;nicamente la primer flota en el phalanx (By jstar).-
',

'2.10.0' => ' 06/01/12

-[Mejoras]
-- PLANTILLAS
--- Organizadas las plantillas restantes en carpetas.-
--- Aplicada una proteccion extra a las carpetas de las plantillas para evitar que se listen los archivos dentro de ellas.-
--- Las plantillas .tpl ahora requieren extensi&oacute;n .php, esto es para mayor protecci&oacute;n y para que sea m&aacute;s simple trabajarlo.-
--- Creadas las plantillas correspondientes para la p&aacute;gina de amigos/compa&ntilde;eros.-
--- Creadas las plantillas correspondientes para la p&aacute;gina de mensajes.-
--- Creadas las plantillas faltantes para la p&aacute;gina de la galaxia.-
--- Pulida la p&aacute;gina de baneos y agregada la plantilla faltante.-
--- Mejorada la muestra de errores al enviar un mensaje.-
--- Creadas las plantillas faltantes para la p&aacute;gina class.ShowFleetPage.php.-
--- Creadas las plantillas faltantes para la p&aacute;gina class.ShowFleetPage1.php.-
--- Creadas las plantillas faltantes para la p&aacute;gina class.ShowFleetPage2.php.-
--- Creadas las plantillas faltantes para la p&aacute;gina class.ShowFleetPage3.php.-
--- Creadas las plantillas faltantes para la p&aacute;gina class.ShowFleetACSPage.php.-
--- Creadas las plantillas faltantes para la p&aacute;gina class.ShowAlliancePage.php.-
--- La plantilla fleetACS_table.php fue reorganizada. La misma contiene &uacute;nicamente la tabla de agregar miembros y cambiar nombre; para el resto se reutiliza la plantilla fleet_table.php.-
--- Directorio templates renombrado a views.-

-- IDIOMA
--- Los archivos de lenguaje ahora requieren extensi&oacute;n .php, esto es para mayor protecci&oacute;n y para que sea m&aacute;s simple trabajarlo.-
--- La p&aacute;gina de mensajes privados ahora podr&aacute; estar en varios idiomas.-
--- Se completo las descripciones cortas del idioma espa&ntilde;ol para los edificios lunares y terraformer.-
--- Correci&oacute;n menor de idioma en la p&aacute;gina de SACs.-
--- No se le da m&aacute;s soporte al paquete de idioma franc&eacute;s, s&oacute;lo espa&ntilde;ol e ingl&eacute;s.-

-- CORE
--- Codificaci&oacute;n de caracteres cambiada de iso-8859-1 a UTF-8.-
--- Copyrights actualizados, reducido el peso de los archivos en un 45%.-
--- Eliminados los $phpEx.-
--- C&oacute;digo pulido y estandarizado bajo un mismo formato.-
--- Se reemplazo la variable global $xgp_root por la constante XGP_ROOT.-
--- Se reemplazo la variable global $dpath por la constante DPATH.-
--- Eliminada la variable global $game_config, fue reemplazada por la funci&oacute;n read_config().-
--- Nueva funci&oacute;n read_config (lee la configuraci&oacute;n requerida y retorna su valor).-
--- Archivos js comprimidos, la carga por el lado del cliente ser&aacute; mucho m&aacute;s r&aacute;pida.-
--- P&aacute;ginas seteadas dentro de una clase.-
--- El js overlib ya no se carga m&aacute;s en el login.-
--- Optimizaci&oacute;n de querys en la p&aacute;gina de investigaci&oacute;n (sobrecarga de querys cuando estaba desarrollada la red de invetigaci&oacute;n).-
--- common.php renombrado a global.php.-
--- Directorio scripts renombrado a js.-
--- Se reemplazo la tabla config por un archivo xml (se redujo 1 query en todo el juego).-
--- Removida la variable de configuraci&oacute;n BuildLabWhileRun, no era utilizada.-

-- ALLIANCE
--- Se quito el bbCode de class.ShowAlliancePage.php y se movi&oacute; a class.BBCode.php.-
--- El m&eacute;todo MessageForm fue renombrado a message_box.-
--- Nuevo m&eacute;todo return_rank, retorna el rango del usuario.-
--- Nuevo m&eacute;todo return_sort, retorna el orden seleccionado por el usuario.-
--- Mejorado el borrado de la alianza, antes realizaba 1 query por cada usuario a borrar, ahora realiza &uacute;nicamente una.-

-- FLEET
--- Tiempo de expedici&oacute;n reducido de 10 a 5 horas.-
--- Tiempo de mantener posici&oacute;n establecido por defecto en 1 y no en 0.-
--- Optimizaciones en class.ShowFleet1Page.php.-
--- Optimizaciones en class.ShowFleetACSPage.php.-
--- Nuevo selector de atajos / shortcuts en la p&aacute;gina 1 del env&iacute;o de flotas (class.ShowFleet1Page.php).-
--- Emprolijado el c&oacute;digo y mejoras en la p&aacute;gina 1 del env&iacute;o de flotas (class.ShowFleet1Page.php).-
--- Emprolijado el c&oacute;digo y mejoras en la p&aacute;gina 2 del env&iacute;o de flotas (class.ShowFleet2Page.php).-
--- Emprolijado el c&oacute;digo y mejoras en la p&aacute;gina 3 del env&iacute;o de flotas (class.ShowFleet3Page.php).-
--- Emprolijado el c&oacute;digo y mejoras en la p&aacute;gina SAC del env&iacute;o de flotas (class.ShowFleetACSPage.php).-
--- Corregido un bug menor que no mostraba el nombre del SAC en el t&iacute;tulo de la p&aacute;gina de SACs (antes mostraba un c&oacute;digo extra&ntilde;o).-

-- MENSAJES
--- P&aacute;gina de mensajes revisada y emprolijada.-
--- Mejorada la velocidad de carga en la p&aacute;gina de mensajes.-

-- OFICIALES
--- Eliminados todos los oficiales secundarios, se dejaron los del OGame original.-
--- Los oficiales producen los mismos efectos que el OGame original.-
--- Los costes tambi&eacute;n fueron copiados (Editable desde vars).-
--- Imagenes de los oficiales movidas al directorio del skin xgproyect.-
--- Cantidad de materia oscura requerida por el mercader actualizada de 2500 a 3500.-

-- INSTALADOR / ACTUALIZADOR
--- Al instalar por primera vez el juego se hacen 7 querys menos durante todo el proceso.-
--- Requiere email y clave del administrador para validar la actualizaci&oacute;n.-
--- El sistema de encarga autom&aacute;ticamente de determinar los datos del servidor.-
--- El usuario ya no debe seleccionar m&aacute;s la versi&oacute;n que tiene, se determina autom&aacute;ticamente.-
--- El sistema de actualizaci&oacute;n no funciona con versiones no compatibles ni con versiones no oficiales, ya que es autom&aacute;tico.-

-- PAGINAS VARIAS
--- Ahora en las opciones de usuarios se puede elegir el directorio del skin que se desea usar (siempre y cuando el administrador haya creado otros skins).-
--- P&aacute;gina de amigos/compa&ntilde;eros reprogramada.-
--- Mejoras menores en la p&aacute;gina de la galaxia.-
--- class.ShowFleetPage.php optimizado de 2 consultas a 1.-
--- Reducci&oacute;n de querys global en el panel administrativo al cargar y realizar acciones.-

-[Fixs]
#----: Corregido un bug que no mostrar&iacute;a correctamente un skin seleccionado por un usuario.-
#7676: No crea destructores y error en estrella de la muerte.-
',

'2.9.10' => ' 29/09/11
*** foundation of the development team(Lucky,Green,Think,Jstar) ***

-[Mejoras]
Incluido un class.ShowInfosPage.php optimizado (by Think).-
Optimizada importantemente la 2&ordf; p&aacute;gina de lanzamiento de flota (by jtsamper/Think)

Inclu&iacute;do idioma franc&eacute;s (by Mizur).-
C&oacute;digo repetido en class.ShowShipyardPage.php (by Alivan).-
Rewritten ShowShortcutsPages.php with new 3 templates (by Jstar).-
Less tpl-loads from HDD in some files (by Jstar).-
Deleted unnecessary and heavy protection system in generalFunction.php (by Jstar).-
Invisible debris like ogame (by Jstar).-

-[Fixs]
Arreglado un error con el nivel de edificio construyendo al cancelar en la cola de construcci&oacute;n (by Think)
Se pueden hacer saltar sat&eacute;lites solares por el salto cu&aacute;ntico (by Think)
Agujero de seguridad en flotas (by Think).-
Formula de energ&iacute;a del sint. de deuterio (by Think).-
Un SAC diferentes destinos (by Tomtom).-
FIX expedicion nunca es destruida completamente (by Tomtom,Think).-
No llega el informe de batalla (by Lucky,Think).-
Deathstar speed (by Jstar).-
Ships disappeared in spy mission (by Jstar).-
Rapid fire of small cargo (by Jstar).-
Old code not deleted (by Jstar).-
Edit moon in ACP (by Jstar).-
Buildings for free (by Jstar).-
Planets destroyed don\'t disappear (by Jstar).-
Planet size (by Jstar).-
Player position and alliance total points in search page.-
Hyperspace Portal redirect (by Quaua).-
Varios fix time (by Quaua).-
Send Fleet Back after AKS (youhou35).-

',

'2.9.9' => ' 07/04/11

-[Seguridad]
- Cerrados algunos agujeros de seguridad (By shoghicp).-

-[Mejoras]
- Se eliminaron los campos id_owner1 y id_owner2 definitivamente de la tabla rw y se reemplazo por owners (Gracias akademik).-

-[Fixs]
#1: Lanzamiento de misiles (By Think, alivan, tomtom).-
#5167: En el mensaje global se ven todos los c&oacute;digo HTML (By tomtom).-
#7628: La columna Acciones no aparece en galaxia.-
#7629: Diferencia Planta de Fusi&oacute;n (By alivan).-
#7630: La administraci&oacute;n se puede banear a si misma (By Think).-
#7631: Diversos bugs misiles (By Think, alivan, tomtom).-
#7632: Tiempos negativos en construcciones (By alivan).-
#7633: No se muestra el &uacute;ltimo mensaje en el panel de administraci&oacute;n (By alivan).-
#7634: hack: ships to colonization (By jstar).-
#7635: Hack phalanx (By jstar).-
#7636: hack destruction mission (By jstar).-
#7637: Recycling without debris (By jstar).-
#7640: CSRF attack (By jstar).-
#7641: xss desde panel administrativo (By jstar).-
#7643: Spy probos capacity (By jstar).-
#7644: mantener la posici&oacute;n de amigos (By jstar).-
#7646: Mandar naves a espiar (By Think).-
#7649: Terraformer no cuesta energ&iacute;a (By Think).-
#7651: resources no update before fleet arrive (By jstar).-
#7655: overflow (By jstar).-

',

'2.9.8' => ' 09/01/11

-[Seguridad]
- Cerrado un agujero de seguridad (By jstar).-

-[Velocidad] (By lucky)
- Algunas mejoras globales en el c&oacute;digo.-
- Algunas mejoras en el c&oacute;digo de la galaxia.-
- Reducci&oacute;n de Querys Gloabales (11 a 9).
- Reducci&oacute;n de Querys en la Galaxia (45 a 9).

-[Mejoras]
- Mejoras en las expediciones (By jstar).-
- Mejora en el calculo de materia oscura (By h2swider).-

- [Fixs]
#4547: No se le puede cambiar el nombre a los sacs (By Lugii) (Gracias Kalax).-
#7617: bug maintaining position (By jstar).-
#7622: sql injection (By jstar).-
#7624: buddy message (By jstar - Think).-
#7625: Fallo en accesos directos (By Think).-
#7626: Falla de seguridad en el hangar (By Think).-
#7627: Bug en la cola de construcci&oacute;n (By jstar).-
',

'2.9.7' => ' 09/12/10

- [Fixs]
#4546: Problemas con los tiempos en los edificios (By jstar).-
#4869: Error al intentar construir algunos edificios inexistentes (By alivan).-
#5018: Calculo err&oacute;neo del l&iacute;mite de la p. novatos (By alivan).-
#5168: errores en la seccion de alianzas y en msjs (By lucky).-
#5169: Error env&iacute;o de misiles sin deuterio y error en &mode=3 en tu propio sistema (By alivan).-
#6017: Fuegos r&aacute;pidos (By lucky).-
#7610: minifix showFleetAcsPage.php (By jstar).-
#7612: Mas campos en el planeta agregando una base lunar a su luna(ACP) (By alivan).-
#7613: Buildings time and price (By jstar).-
#7616: Bug in buildings tail (By jstar).-
#7618: Proteccion de Novatos en los misiles (By tomtom).-
#7619: solicitudes alianza (By jstar).-
#7620: Call back fleet in placement mission (By jstar).-
',

'2.9.6' => ' 16/08/10

- [Seguridad] Validaciones globales para prevenir SQL Injections.-
- [Seguridad] Cerrados varios agujeros de seguridad que permitian SQL Injection.-
- [Seguridad] Corregido un bug de seguridad en los bbcode de la alianza (By slave7).-
- [Seguridad] Corregidos varios bugs de seguridad en las p&aacute;ginas de la alianza y opciones.-

- [Fixs]
#0009: Alianza Contador de caracteres
#0010: Contador de caracteres mensaje
#0011: Texto de la solicitud de alianza
#0013: Cancelar construcciones
#0014: Problemas con nuevas versiones
#0015: Link roto en actualizaci&oacute;n
#4485: El Administrador desaparece por inactivo
#4534: Fuego r&aacute;pido no se calcula
#4535: Mantener posicion
#4536: Problemas con el phalanx
#4537: Error en el lenguaje
#4538: Bug en misi&oacute;n de expedicion
#4539: Peque&ntilde;o bug visual en vista de mensajes
#4540: Saltos en la galaxia
#4558: Bug de seguridad
#4719: Problemas nanobots
',

'2.9.5' => ' 29/07/10

- [Seguridad] Corregidos varios bugs de seguridad (By slave7).-
- [Seguridad] Corregidos varios bugs de seguridad (By jtsamper).-

- [Cambio] Adaptado para que sea compatible con PHP 5.3.3.-
- [Cambio] Aparecen todas las personas que aportaron algo en los cr&eacute;ditos del panel administrativo.-


- Vieja numeraci&oacute;n
- [Fix][Bug #5] Los sacs no coordinan los tiempos (By slave7).-

- Numeraci&oacute;n del viejo Bug Tracker (http://sourceforge.net/apps/mantisbt/xgproyect/view_all_bug_page.php)
- [Fix][Bug #0000011] Los Sacs reparten los recursos (By slave7).-
- [Fix][Bug #0000065] Todas las rondas aparecen como ganadas y a eventualmente empatan (By Nickolay).-
- [Fix][Bug #-------] La planta de fusi&oacute;n funcionaba al 100% cuando el planeta no ten&iacute;a deuterio (By slaver7)

- Nueva numeraci&oacute;n (Arctic Tracker)
- [Fix][Bug #0000002] Corregido el link que da m&aacute;s informaci&oacute;n sobre la protecci&oacute;n de novatos.-
- [Fix][Bug #0000003] Corregido el bug en la alianza que causaba problemas para redirigir al sitio de la alianza.-
- [Fix][Bug #0000004] Bug en la producci&oacute;n, produce m&aacute;s de lo que debe (By Calzon).-
- [Fix][Bug #0000005] Bug en el incremento de puntos de las tecnolog&iacute;as (By Think).-
- [Fix][Bug #0000006] Bug en el Debug Log (By Green).-

',

'2.9.4' => ' 02/03/10
- [Novedad] Ahora el mercader requiere materia oscura (2500), configurable desde constants.php.-

- [Seguridad] Limitado desde la base de datos la cantidad de cupulas que pueden ser contruidas (Gracias a medel).-
- [Seguridad] Mejora de seguridad en el formulario de los misiles.-

- [Fix][Bug #0000031] Misi&oacute;n expedici&oacute;n da escuadron fantasma.-
- [Fix][Bug #0000035] Se pueden enviar misiles interplanetarios a usuarios en vacaciones (By Neurus).-
- [Fix][Bug #0000036] Se pueden enviar misiles interplanetarios en negativo.-
- [Fix][Bug #0000037] Se puede atacar con misiles interplanetarios a usuarios fuertes y debiles (By Neurus).-
- [Fix][Bug #0000038] Luego de un error o advertencia en el envio de misiles no se vuelve a galaxia.-
- [Fix][Bug #0000039] Al colonizar con recursos estos desaparecen.-
- [Fix][Bug #0000043] En INGAME.mo hay 2 entradas iguales.-
- [Fix][Bug #0000044] Varias cupulas de protecci&oacute;n (By Neko).-
- [Fix][Bug #0000047] Error en template estadisticas.-
- [Fix][Bug #0000048] Recursos negativos.-
- [Fix][Bug #0000050] Ataques con sondas de espionaje.-
- [Fix][Bug #0000056] Error al editar usuario en el panel administrativo (By Neko).-
- [Fix][Bug #0000059] Solucionado el error que mostraba registros dobles en las estad&iacute;sticas.-
- [Fix][Bug #-------] Fallo producido al recargar la p&aacute;gina de los edificios, defensas, tecnologias y naves (By Neko).-
- [Fix][Bug #-------] Corregido un bug que no instalaba la tabla para los plugins.-

- [Fix][Bug #-------] Corregido un bug que no ten&iacute;a en cuenta a la supernova en las expediciones.-
- [Fix][Bug #-------] Corregido un bug que ocurria por tener menos deuterio que combustible a gastar (By Neko).-
- [Fix][Bug #-------] No se mostraba correctamente el mensaje de modo vacaciones (By Neko).-
- [Fix][Bug #-------] Si la cuenta est&aacute; en modo borrar y en modo vacaciones, en el overview se le da prioridad al mensaje de borrado de la cuenta.-
- [Fix][Bug #-------] Corregido un bug menor en la creaci&oacute;n de planetas (By Kloud).-
- [Fix][Bug #-------] Corregido un bug menor en el js que maneja los recursos al enviar una flota (By Neko).-

- [Cambio] Actualizados los links de soporte y reporte de bugs del overview en el panel administrativo.-
- [Cambio] Mejoras en los reportes.-
----------- Ahora en la primer ronda siempre se listan las flotas completas.-
----------- Ahora las sondas son destruidas en la primer ronda; al atacante le sale destrucci&oacute;n en la primer ronda, al defensor el reporte completo.-
----------- Ya no se duplica la primer ronda en las batallas de 2 rondas; en la primera se muestran las flotas completas y en la segunda el mensaje destruido.-
- [Cambio] Modificada la licencia para el 2010.-
',

'2.9.3' => ' 12/02/10
- [Seguridad] Mejoras de seguridad en el movimiento de recursos (By calzon).-

- [Fix][Bug #0000040] Multiplicaci&oacute;n de recicladores (By tomtom).-
- [Fix][Bug #-------] Bug que permit&iacute;a el incremento de naves en el salto cu&aacute;ntico (By war4head).-

- [Cambio] Nuevamente se volvio al viejo reloj en la visi&oacute;n general.-

# Version 0.2 del panel administrativo

.- Los moderadores y operadores ya no podran:
 || Suspender administradores.
 || Crear usuarios con rango mayor al de un jugador.
 || Reiniciar universo.
 || Ejecutar consultas SQL.
 || Editar datos personales.
 || Editar/Vaciar el historial.
.- [MINOR ADD] Adherida la ID en el sistema de suspensi&oacute;n.
.- Agregado un historial en el panel administrativo, la cual custodiara los movimientos de los siguientes archivos:
 || BanPage.php
 || ErrorPage.php
 || AccountEditorPage.php
 || Moderation.php
 || QueriesPage.php
 || ResetPage.php
 || AccountDataPage.php
 || CreateNewUserPage.php
 || GlobalMessagePage.php
 || DataBaseViewPage.php
 || ConfigStatsPage.php
 || SettingsPage.php
 || SearchInDBPage.php
.- Logs protegidos con .htaccess para que nadie pueda leerlos.
.- Agregada opci&oacute;n de modo vacaciones en Datos Personales.
.- Agregado de vuelta el scrolling en el frame (No funciona en IE).
.- Peque&ntilde;o fix en la lista de mensajes.
.- Las siguientes funciones han sido reemplazadas por una redirecci&oacute;n al buscador avanzado:
 || Lista de jugadores.
 || Lista de lunas.
 || Lista de planetas.
 || Lista de usuarios conectados.
 || Lista de planetas activos.
.- Agregado al buscador avanzado:
 || Expandir / contraer.
 || Paginaci&oacute;n.
 || Nuevos filtros y tipos de b&uacute;squeda.
.- [FIX] borrar usuarios (No borraba las colonias de la tabla xgp_planets).
.- [FIX] bugs peque&ntilde;os en el ejecutador de consultas SQL.
.- Cambios en el ADMIN.mo y traduccion completa al ingl&eacute;s. (Gracias Arali)
.- Agregada nueva funci&oacute;n -> DeleteSelectedPlanet.
',

'2.9.2' => ' 01/12/09
- [Seguridad] Cerrado un agujero en la alianza que permit&iacute;a sql injection (Gracias a privatethedawn).-

- [Novedad] Implementado un nuevo sistema de Plugins v0.3 (Gracias adri93).-
- [Novedad] Ahora cuando hay muchos recursos en la cuenta de un usuario se muestra K,M,B,T,T+ en la informaci&oacute;n de cuentas del panel del admin.-
- [Novedad] Hora y fecha del servidor, en el overview, en Castellano. (Sustituir en la linea 402 el (es_ES), por el idioma deseado.).-

- [Fix][Bug varios] Fueron corregidos varios bugs menores.-
- [Fix][Bug #0000019] Famoso error en las estad&iacute;sticas provocado por una query que no era finalizada.-
- [Fix][Bug #0000020] Informaci&oacute;n de cuentas en el panel del admin.
- [Fix][Bug #-------] No se muestra bien el tiempo de estacionar en aliado en el panel del admin.-
- [Fix][Bug #-------] Corregidos varios textos (espa&ntilde;ol,ingl&eacute;s) en el panel de administraci&oacute;n.-
- [Fix][Bug #-------] Arreglado peque&ntilde;o error en los datos mostrados en la seccion de recursos.-
- [Fix][Bug #-------] Ahora muestra correctamente la inactividad de los jugadores de tu alianza desde.-

- [Fix][Bug #-------] Al borrar un usuario se borraran todos sus planetas.-


- [Cambio] Algunas mejoras de optimizaci&oacute;n en la informaci&oacute;n de cuentas en el panel administrativo.
- [Otros]  Mejoras en el sistema de envio de flotas del FleetAjax.php
',

'2.9.1' => ' 01/11/09

- [Fix][Menor] Corregido un bug menor en el texto de los informes de espionaje que permit&iacute;a bashing.-
- [Fix][Menor] Corregido un bug menor en el texto de los reportes de combates.-
- [Fix][Menor] Corregido un bug menor que mostraba car&aacute;cteres raros en la alianza luego de editar un texto (By Neko).-
- [Fix][Menor] Corregido un bug menor que deformaba la p&aacute;gina de los mensajes al enviar mensajes muy largos (By Neko).-
- [Fix][Menor] Correciones en algunos textos.-

Nueva numeraci&oacute;n (bug tracker)
- [Fix][Bug #0000010] No direcciona bien el ataque.-


Vieja numeraci&oacute;n
- [Fix][Bug #5] Los sacs no coordinan los tiempos.-
- [Fix][Bug #6] Los sacs no aparecen en el mismo mensajes en la visi&oacute;n general (By privatethedawn).-



- [Cambio] Ahora cuando se estan moviendo flotas no es posible abandonar un planeta (By privatethedawn).-
- [Cambio] Ahora al explorar la galaxia, si es tu propio sistema solar, no perder&aacute;s deuterio.-
- [Cambio] Nuevo panel administrativo (By Neko)
.- Nuevo skin
.- Agregado el sistema de moderaci&oacute;n.
.- FIX suspender usuario (Si el jugador ya estaba suspendido y lo suspendias de vuelta creaba
otra tabla con los mismos datos (duplicacion)). Mejoras.
.- Reset de universo mejorado, con poder de reiniciar distintas cosas.
.- [FIX] Al resetear todo el universo:
 || no introducia el "id_level" en la tabla de planetas.
 || dejaba al usuario como inactivo (al reiniciar nuevamente se borraban las cuentas por inactividad).
.- Opciones de lunas mejorado.
.- Agregada la creaci&oacute;n de planetas.
.- Contador de caracteres para mensajes (cntchar.js) nuevo y mejorado.
.- Agregada la opcion de "ver todos los mensajes" y un "seleccionar todo" en la lista de mensajes.
.- Agregado sistema para crear cuentas.
.- Reestructuraci&oacute;n del adminresources.php (ahora AccountEditorPage.php)
 || Agregada la edici&oacute;n de datos personales.
 || Agregada varias opciones m&aacute;s para la edici&oacute;n de planetas y lunas.
 || Agregada la edici&oacute;n de oficiales.
.- Agregado un buscador avanzado.
.- Eliminado el borrado de reportes cuando se borraba un usuario, sino cuando el otro atacante o defensor queria ver
la batalla no le figuraba.
',


'2.9.0' => ' 21/10/09

- [Seguridad] Ahora es encriptada la contrase&ntilde;a que se ingresa durante la actualizaci&oacute;n.-

- [Cambio] Optimizaci&oacute;n y mejora general en el manejo de las lunas (By angelus_ira).-
- [Cambio] Mejora en el rendimiento de algunas p&aacute;ginas.-
- [Cambio] Ahora al explorar la galaxia, si es tu propio sistema solar, no perder&aacute;s deuterio.-

- [Fix][Bug #122] Problemas en la actualizaci&oacute;n de los puntos.-
- [Fix][Bug #123] Bug en los reportes mostrando que se produjo un empate cuando no fue asi.-
- [Fix][Bug #124] Problemas con el phalanx.-
- [Fix][Bug #125] Bug en la lista de amigos no permite aceptar/rechazar.-
- [Fix][Bug #126] No le aparecen los reportes de ataque al defensor.-
- [Fix][Bug #127] Problema con el title de los recursos maximo en las flotas.-
',

'2.8' => ' 10/10/09

- [Seguridad] Mejora de seguridad en varias funciones y archivos.-
- [Seguridad] Mejora de seguridad y prevenci&oacute;n de n&uacute;meros y caracteres no permitidos en el salto cu&aacute;ntico (Gracias a Trojan).-

- [Novedad] El pack en ingl&eacute;s se encuentra 100% traducido (Gracias war4head).-
- [Novedad] Se definieron constantes para los oficiales, asi es m&aacute;s f&aacute;cil editarlos (no lo recomiendo).-

- [cambio] Mejoras varias en el script que calcula los ataques.-
- [Cambio] Corregido un bug de seguridad al agregar recursos al envio de una flota (Gracias a MSW).-
- [Cambio] Los reportes de combate de la secci&oacute;n mensajes ahora solo muestran un link al reporte y no un resumen.-
- [Cambio] Peque&ntilde;o fix y mejora de seguridad para el phalanx y el alcance (Gracias a Trojan).-
- [Cambio] Optimizado el alcance del phalanx, reducida la carga de procesamiento (Gracias a Trojan).-
- [Cambio] Optimizados los movimientos de flotas (Gracias a Trojan).-
- [Cambio] Los recicladores valen ahora 10.000 de metal - 6.000 de cristal - 2.000 de deuterio como corresponde.-

- [Fix][Varios] Correcci&oacute;n de varios bugs menores globales.-
- [Fix][Varios] Corregidos varios bugs menores en el armado de las estad&iacute;sticas.-
- [Fix][Varios] Corregidos varios bugs menores en los movimientos de flotas y misiones.-
- [Fix][Bug #108] Coordenadas erroneas en el mensaje de reciclaje.-
- [Fix][Bug #109] Los jugadores de la lista de compa&ntilde;eros aparecen siempre como desconectados.-
- [Fix][Bug #110] Duplicaci&oacute;n de recicladores al hacer multiple clicks en la galaxia.-
- [Fix][Bug #111] Jugadores inactivos y borrados no son eliminados fisicamente.-
- [Fix][Bug #112] No son actualizados los recursos del planeta de un jugador al ser atacado.-
- [Fix][Bug #113] Peque&ntilde;o bug que muestra un mensaje de error en la pagina de los sacs.-
- [Fix][Bug #114] Bug al intentar eliminar un jugador.-
- [Fix][Bug #115] Diferencia de estados entre (debil, fuerte) al enviar una flota y la galaxia.-
- [Fix][Bug #116] Bug en la misi&oacute;n de destrucci&oacute;n.-
- [Fix][Bug #117] Bug en la alianza que no descuenta los jugadores que salen de ella.-
- [Fix][Bug #118] Bug que permite ignorar las naves e investigaciones, permitiendo edificar robots, nanos y laboratorio.-
- [Fix][Bug #119] Bug en los textos de transportes.-
- [Fix][Bug #120] Bug en las batallas de una sola ronda.-
- [Fix][Bug #121] Bug en las solicitudes de la alianza.-
',

'2.7' => ' 17/09/09

- [Novedad] Sistema para ver toda la informaci&oacute;n de la cuenta de un usuario. By Neko.-
- [Novedad] SACs implementeados [BETA].-
----------- C&oacute;digo: MadnessRed
----------- Testeos y funcionamiento, como la adapaci&oacute;n a la 0.9a: cyberrichy
----------- Adaptaci&oacute;n a la 2.7: lucky

- [Fix][Varios] Varios bugs menores de estilo, plantillas y lenguaje reparados.-
- [Fix][Bug #93] Bug al eliminar un jugador.-
- [Fix][Bug #94] No se muestra la cantidad de flotas en el panel de administraci&oacute;n.-
- [Fix][Bug #95] Deuterio en negativo a causa de la planta de fusi&oacute;n.-
- [Fix][Bug #96] Plantillas para solicitud de amigos descolocada.-
- [Fix][Bug #97] No se muestra el estado de baneo y vacaciones en los jugadores dentro del panel de administraci&oacute;n.-
- [Fix][Bug #99] Problemas con la p&aacute;gina de busqueda mostrando demsiados resultados o no permitiendo descender en la p&aacute;gina.-
- [Fix][Bug #100] Al mandar naves de distintos tipos a colonizar vuelve el colonizador y se pierden las otras naves.-
- [Fix][Bug #101] No se generan lunas cuando sale un 20% de probabilidad.-
- [Fix][Bug #102] Al salir de una alianza el n&uacute;mero de miembros no disminuye como deber&iacute;a.-
- [Fix][Bug #103] Problemas al actualizar las estad&iacute;sticas.-
- [Fix][Bug #104] Cuando construis nanobots o robots te permite hacer naves y defensas.-
- [Fix][Bug #105] Bug menor en la alianza la querer editar el rango de un usuario cuando no existen rangos.-
- [Fix][Bug #106] Se encuentran invertidas las temperaturas de las lunas.-
- [Fix][Bug #107] Los planetas destruidos son contados como planetas activos.-
',

'2.6' => ' 24/08/09

- [Fix][Varios] Varios bugs menores de estilo, plantillas y lenguaje reparados.-
- [Fix][Bug #28] Bug en la cola de produccion del hangar.-
- [Fix][Bug #73] Problemas con el reporte de porcetaje de luna.-
- [Fix][Bug #78] Problemas con la lista de construcci&oacute;n.-
- [Fix][Bug #82] Se pueden mover flotas desde la galaxia cuando un jugador esta en modo vacaciones.-
- [Fix][Bug #83] Los reportes de ataque muy largos no se pueden ver completos.-
- [Fix][Bug #85] La cantidad de recursos para construir siempre se queda en rojo.-
- [Fix][Bug #84] Si la luna esta llena, no te permite desmontar edificios.-
- [Fix][Bug #86] Bug que provoca errores en las alianzas.-
- [Fix][Bug #87] Bug en el estilo del panel de administraci&oacute;n, mostrando imagenes del juego.-
- [Fix][Bug #88] Bug menor que muestra un texto que fue utilizado para debug.-
- [Fix][Bug #89] Bug que permite la ampliacion del hangar cuando se estan construyendo flotas y defensas.-
- [Fix][Bug #90] Problemas con la misi&oacute;n de destrucci&oacute;n.-
- [Fix][Bug #91] No funciona correctamente el almirante.-
- [Fix][Bug #92] No funciona correctamente el almacenista.-

- [Cambio] Ahora la lista de planetas se muestra en el panel de admnistraci&oacute;n por id de menor a mayor.-
',

'2.5' => ' 18/08/09

- [Novedad] Sistema de advertencias y errores en el panel de administraci&oacute;n (tambi&eacute;n informa la existencia de una nueva versi&oacute;n).-
- [Novedad] Ahora puedes determinar si al banear un jugador este debe entrar o no en modo vacaciones.-
- [Novedad] Gracias a 3R1K que tradujo el panel de administraci&oacute;n en su totalidad al idioma ingl&eacute;s.-
- [Novedad] Redise&ntilde;ado el panel de administraci&oacute;n.-
- [Novedad] Ahora la p&aacute;gina de mensajes respeta el formato del OGame original, mostrando a los operadores del juego.-
- [Novedad] Ahora cuando borras un planeta aparece planeta destruido como en el OGame original.-
- [Novedad] El misil interplanetario ahora requiere el hangar en nivel 1 y el motor de impulso en 1 como en el OGame original.-
- [Novedad] El misil de intercepci&oacute;n ahora requiere el hangar en nivel 1 como en el OGame original.-
- [Novedad] El silo ahora requiere el hangar en nivel 1 como en el OGame original.-

- [Fix][Varios] Varios bugs menores de estilo, plantillas y lenguaje reparados.-
- [Fix][Bug #14] No se pueden usar skins externos.-
- [Fix][Bug #47] Bug que provoca en IE que no se vea el menu y quede desplazado hacia arriba al bajar demasiado en una p&aacute;gina.-
- [Fix][Bug #61] Problemas en el conteo de los jugadores en la visi&oacute;n general.-
- [Fix][Bug #62] Peque&ntilde;o bug que no muestra los stats correctamente en el overview.-
- [Fix][Bug #63] Bug en la funci&oacute;n del manejo de las sesiones.-
- [Fix][Bug #64] Bug con los permisos y con las lunas, evitando que sean atacadas.-
- [Fix][Bug #65] Bug que no permite espiar desde flota pero si desde la galaxia.-
- [Fix][Bug #66] Problemas en los niveles del phalanx.-
- [Fix][Bug #67] Producci&oacute;n ilimitada de misiles.-
- [Fix][Bug #68] Bug en el link para Continuar/Volver luego de enviar un mensaje circular en la alianza.-
- [Fix][Bug #69] Bug que muestra mal los mensajes de error cuando un usuario no esta logueado.-
- [Fix][Bug #70] Problemas con el salto de l&iacute;nea en los mensajes circulares.-
- [Fix][Bug #71] Problemas con la misi&oacute;n estacionar aliado, muestra un mensaje de que el jugador es muy fuerte.-
- [Fix][Bug #72] Bug en la diferencia de energ&iacute;a consumida actual, anterior y posterior.-
- [Fix][Bug #74] No se puede abandonar la alianza.-
- [Fix][Bug #75] Problemas en la actualizaci&oacute;n de puntos de algunos jugadores.-
- [Fix][Bug #76] La misi&oacute;n de espionaje ignora el estado de vacaciones desde la visi&oacute;n de galaxia.-
- [Fix][Bug #77] Al hacer regresar una flota con misi&oacute;n mantener posici&oacute;n no descuenta los tiempos correctamente.-
- [Fix][Bug #79] Bug que permite incrementar las tecnolog&iacute;as (Gracias a mikey302 y death).-
- [Fix][Bug #80] Bug en el limite de envio m&aacute;ximo de expediciones.-
- [Fix][Bug #81] Bug en la visi&oacute;n general de la alianza, mantiene el nombre "Fundador" del fundador de la alianza.-

- [Cambio] Ahora el n&uacute;mero de la versi&oacute;n se obtiene de la base de datos.-
- [Cambio] Mejoras de seguridad en la p&aacute;gina de la alianza.-
- [Cambio] Noticias removidas.-
- [Cambio] Mejorada la velocidad en que se genera la p&aacute;gina de estad&iacute;sticas y algunos cambios menores.-
- [Cambio] Nuevas alertas de presencia del directorio install y de escritura del archivo config.php.-
- [Cambio] Los recicladores ahora s&oacute;lo pueden transportar 20.000 unidades de recursos como en el OGame original.-
- [Cambio] Removida la p&aacute;gina de contacto, ahora puedes encontrar a los adms/mods/oper en el panel de mensajes como en el OGame.-

- [Cambio] Ahora a la derecha del nombre de la luna aparece la referencia (Luna) como en el OGame original.-
- [Cambio] Ahora si en la visi&oacute;n general tienes seleccionada la luna no se mostrar&aacute; esta otra vez a la izquierda.-
- [Cambio] Reformas en la galaxia en c&oacute;digo y organizaci&oacute;n de las clases y plantillas.-
- [Cambio] Juego adaptado al skin original del OGame, ahora el juego ser&aacute; a su vez compatible con cualquier skin.-
',


'2.4' => ' 25/07/09

- [Seguridad] Protecci&oacute;n con .htaccess de la carpeta includes, language y templates.-
- [Seguridad] Protecci&oacute;n con .htaccess de global.php, config.php y extension.inc.php.-
- [Seguridad] Cambio en el protecci&oacute;n de carpetas.-
- [Seguridad] Mejoras de seguridad en diversos archivos.-

- [Novedad] Nuevo sistema de estad&iacute;sticas, m&aacute;s r&aacute;pido, m&aacute;s simple, menos querys, menos carga, y nuevo panel para administrarlas; (By angelus_ira) Muchas gracias =) .-
- [Novedad] Nuevo sistema de idioma, ahora el idioma es configurable desde el panel de administraci&oacute;n, seleccionando un lenguaje a la vez.-
- [Novedad] La tecnolog&iacute;a de espionaje funciona como en el ogame original:
-------- Nivel 0 a 1 -> Sin datos de la flota.-
-------- Nivel 2 a 3 -> N&uacute;mero total de naves.-
-------- Nivel 4 a 7 -> N&uacute;mero total de naves y el tipo de las naves.-
-------- Nivel 8 -> N&uacute;mero total de naves, tipo de nave y cantidad de cada tipo.-

- [Novedad] Ahora el administrador puede decidir si los administradores y/o moderadores se les actualizar&aacute;n o no los puntos.-
- [Novedad] Ahora el administrador puede decidir si los administradores y/o moderadores pueden o no recibir ataques de otros jugadores.-
- [Novedad] Ahora en las investigaciones te muestra el nivel de espionaje y computaci&oacute;n que te dan los comandantes.-
- [Novedad] Cuando un usuario es baneado, ahora le muestra la fecha en que finaliza su ban.-

- [Fix][Varios] Corregidos varios bugs menores de redirecci&oacute;n, visuales, textos y configuraciones.-
- [Fix][Bug #1] Al achicar la pantalla se superpone el menu de recursos con el resto del cuerpo.-
- [Fix][Bug #2] Ahora cuando una cuenta es baneada, el jugador baneado no podr&aacute; acceder a la cuenta de ningun forma.-
- [Fix][Bug #3]	Al borrar una luna, ya no borrar&aacute; el planeta.-
- [Fix][Bug #4] Reparados algunos bugs en el phalanx.-
- [Fix][Bug #8] Corregida la visi&oacute;n del imperio, ahora muestra las investigaciones.-
- [Fix][Bug #9] Ahora al cerrar el servidor un usuario com&uacute;n no podr&aacute; ingresar.-
- [Fix][Bug #10] Problemas en los permisos de las alianzas.-
- [Fix][Bug #11] Ahora al cerrar el servidor no desaparece m&aacute;s el mensaje con el motivo para cerrarlo.-
- [Fix][Bug #12] Ahora la misi&oacute;n desplegar funciona correctamente.-
- [Fix][Bug #13] La misi&oacute;n estacionar en aliado funciona correctamente, no se repiten m&aacute;s mensajes, ni tampoco produce tiempos negativos cuando se selecciona como tiempo de estacionamiento 0 horas.-
- [Fix][Bug #15] Problemas con la misi&oacute;n de recoleccion o reciclaje, no entrega los recursos a quien corresponde.-
- [Fix][Bug #16] Problemas con los tiempos del hangar al cambiar de planeta, volviendo el tiempo a su estado incial.-
- [Fix][Bug #17] Los oficiales almirante y general funcionan correctamente [oficiales al 100%].-
- [Fix][Bug #18] Problemas con la planta en nivel 1 permitiendo la produccion de recursos (Fixed by zorro2666).-
- [Fix][Bug #19] Ahora si un jugador es fuerte o d&eacute;bil y al mismo tiempo est&aacute; inactivo podr&aacute; ser atacado de todas formas.-
- [Fix][Bug #20] Problemas de caracteres en los textos de registro.-
- [Fix][Bug #21] No funciona el enviar mensaje cuando recibes un ataque.-
- [Fix][Bug #22] Problemas con los mensajes al redactar un mensaje privado en la parte visual.-
- [Fix][Bug #23] Fallan los links que redirigen a la galaxia durante los movimientos de flotas.-
- [Fix][Bug #24] El hangar permite producir igual durante su ampliaci&oacute;n (Fixed by zorro2666).-
- [Fix][Bug #25] Se puede ampliar el hangar mientras de produce (Fixed by zorro2666).-
- [Fix][Bug #26] No sale el nombre del jugador en color verde en las estad&iacute;sticas.-
- [Fix][Bug #27] Bug que permite ampliar el laboratorio e investigar al mismo tiempo.-
- [Fix][Bug #29] Bug en el select de las estad&iacute;sticas al cambiar la p&aacute;gina mostrando la primer p&aacute;gina siempre.-
- [Fix][Bug #30] Problemas con los campos del planeta y las colas de contrucci&oacute;n.-
- [Fix][Bug #32] Bug para ver el reporte de combate desde el panel de administraci&oacute;n.-
- [Fix][Bug #33] Bugs diversos menores en los misiles interplanetarios (Fixed by lordz).-
- [Fix][Bug #34] Bugs menores que no muestran correctamente los textos.-
- [Fix][Bug #35] Posible bug en los almacenes, incrementan menos de lo que deben.-
- [Fix][Bug #36] No aparece el icono para lanzar misiles en la galaxia.-
- [Fix][Bug #37] Bug que provoca que las colonias en la visi&oacute;n general se vean en filas de 5 y no de 2.-
- [Fix][Bug #38] Bug en la misi&oacute;n colonizar, si se alcanz&oacute; el limite de planetas entonces regresa instantaneamente sin respetar los tiempos.-
- [Fix][Bug #39] Bug en las expediciones que provoca la perdida de la materia oscura.-
- [Fix][Bug #40] Bug que no muestra la materia oscura recolectada en las expediciones.-
- [Fix][Bug #41] Peque&ntilde;o bug que provoca un error en el update de puntos (Fixed by angelus_ira).-
- [Fix][Bug #42] Problemas con los textos, se pierden las frases y la cantidad de recursos en algunos reportes.-
- [Fix][Bug #43] Problemas con la proteccion de novatos al enviar sondas desde la galaxia y desde flota.-
- [Fix][Bug #44] Problemas con el js que actualiza los recursos, no teniendo en cuenta la velocidad del servidor.-
- [Fix][Bug #45] Los almacenes funcionan mal, debido a un bug en la relaci&oacute;n de incremento por nivel.-
- [Fix][Bug #46] bbCode ni HTML funcionan en los textos de la alianza.-
- [Fix][Bug #48] Se perdi&oacute; el texto de advertencia en las expediciones.-
- [Fix][Bug #49] Problemas con la actualizaci&oacute;n de puntos de la alianza.-
- [Fix][Bug #50] Problemas con los atajos.-
- [Fix][Bug #51] No aparece el icono de los mensajes para responder.-
- [Fix][Bug #52] Bug en la alianza que probaca un error en la base de datos.-
- [Fix][Bug #53] Problemas de duplicaci&oacute;n en la misi&oacute;n transportar.-
- [Fix][Bug #54] Bug que no permite generar las estad&iacute;sticas (Fixed by angelus_ira).-
- [Fix][Bug #55] Bug que mostraba la luna luego de que fuera destruida.-
- [Fix][Bug #56] Bug que no muestra el porcentaje de creaci&oacute;n de las lunas.-
- [Fix][Bug #57] Bug que provoca que no se muestren los mensajes en las expediciones.-
- [Fix][Bug #58] No se muestran los informes de construcci&oacute;n en el panel del admin.-
- [Fix][Bug #59] Los reportes de construcci&oacute;n no figuran en ninguna categor&iacute;a.-
- [Fix][Bug #60] Bug que permitia obtener tecnolog&iacute;as sin ning&uacute;n costo.-

- [Cambio] Optimizaci&oacute;n del manejo de flotas (By shoghicp).-
- [Cambio] Optimizaci&oacute;n del overview del admin (By jtsamper).-
- [Cambio] Reorganizadas algunas carpetas.-
- [Cambio] Ahora los administradores no podr&aacute;n borrarse a si mismos.-
- [Cambio] Ahora s&oacute;lo los administradores podr&aacute;n borrar jugadores.-
- [Cambio] Reducci&oacute;n de l&iacute;neas y simplificaci&oacute;n en el panel de admnistraci&oacute;n.-
- [Cambio] Finalmente el directorio includes queda definido con 3 carpetas (classes, functions y pages).-

- [Cambio] Integrado el auto-update a index.php en la carpeta install.-
- [Cambio] Cuando se registra un usuario ahora es redirigido directamente dentro del juego.-
- [Cambio] Se volvio al viejo sistema de mensajes.-
- [Cambio] El pack de idioma de divide en 4 archivos:

-------- ADMIN.mo -> TODO LO DEL PANEL DE ADMINISTRACI&oacute;N.-
-------- CHANGELOG.mo -> CHANGELOG TAL Y CUAL COMO ES CONOCIDO.-
-------- INGAME.mo -> TODO EL CONTENIDO INTERNO DEL JUEGO Y NO ACCESIBLE SIN TENER UNA CUENTA.-
-------- PUBLIC.mo -> TODO EL CONTENIDO P&uacute;BLICO DEL JUEGO (index.php, reg.php, clave perdida y contacto).-

- [Cambio] Ahora en la p&aacute;gina de la flota se muestra correctamente la velocidad de las naves al posicionar el mouse sobre el nombre de las mismas.-
- [Cambio] Ahora al realizar cualquier movimiento de flota desde la galaxia, tambi&eacute;n se pasa el valor de la misi&oacute;n por lo que si por ejemplo seleccionas atacar ya aparecer&aacute; marcado en el env&iacute;o de las flotas.-
- [Cambio] Ahora si el jugador esta inactivo podr&aacute; ser atacado, funcionando de la misma forma que en el ogame original.-
- [Cambio] Los archivos ShowFleetPage.php, floten1.php, floten2.php, y floten3.php ahora utilizan plantillas.-
- [Cambio] Finaliza el soporte a las siguientes versiones v1.4a/v1.4b/1.4c,por lo tanto no habr&aacute; m&aacute;s auto-update.php.-
- [Cambio] class.FlyingFleetHandler.php maneja todo lo que sea flotas, misiones y funciones especificas de las mismas.-
- [Cambio] Eliminada la experiencia de guerrero y minero.-
- [Cambio] Las funciones CreateFleetPopupedMissionLink, CreateFleetPopupedFleetLink y BuildHostileFleetPlayerLink fueron hubicadas en class.FlyingFleetsTable.php.-
- [Cambio] Dentro de la clase class.FlyingFleetsTable.php, encontramos las funciones BuildFleetEventTable y BuildFlyingFleetTable y todas las funciones que estas necesitan para funcionar.-
- [Cambio] rw.php ahora se llama CombatReport.php.-
- [Cambio] raketenangriff.php del root cambiado a MissilesAjax.php; y flottenajax.php cambiado a FleetAjax.php.-
- [Cambio] La funci&oacute;n GetNextJumpWaitTime fue movida de GeneralFunctions.php a class.ShOWInfosPage.php.-
- [Cambio] infos.php y jumpgate.php se encuentran en class.ShOWInfosPage.php.-
- [Cambio] Eliminado quickfleet.php no ten&iacute;a utilidad.-
- [Cambio] Home reprogramado y simplificado.-
- [Cambio] Ahora los oficiales esp&iacute;a y comandante son obtenidos desde la base de datos directamente y no suman espionaje y computaci&oacute;n, asi es m&aacute;s f&aacute;cil de administrar.-
- [Cambio] constantes.php nuevamente se llama constants.php y funciones.php se llama ahora GeneralFunctions.php.-
- [Cambio] class.ShowShipyardPage.php maneja la construcci&oacute;n de defensas y naves.-
- [Cambio] Implementado el dise&ntilde;o del OGame original para el overview.-
- [Cambio] Adaptado el dise&ntilde;o de la p&aacute;gina de los oficiales al resto del juego.-

- [Cambio] En la visi&oacute;n de la galaxia no se realizan mas revisiones de los campos de los planetas.-
- [Cambio] La funci&oacute;n sendnewpassword fue integrada al lostpassword en el archivo index.php.-
- [Cambio] Limitada la query que traia los datos en la visi&oacute;n del imperio, reducida un poco la carga.-
- [Cambio] Las funciones de Strings.php fueron movidas a funciones.php.-
- [Cambio] Ahora si no tenes materia oscura para reclutar oficiales aparecer&aacute; el reclutar en rojo y bloqueado.-
- [Cambio] Notar que el manejo de las p&aacute;ginas esta en game.php.-
- [Cambio] Todas las p&aacute;ginas del juego ahora se manejan con funciones y/u objetos.-
- [Cambio] Inicio de la exportaci&oacute;n del c&oacute;digo a objetos.-
- [Cambio] Eliminados los title de las p&aacute;ginas, ahora s&oacute;lo se muestra el nombre del servidor.-
- [Cambio] Ahora la base lunar s&oacute;lo da 3 campos por nivel, tal cual y como es en el ogame original.-
- [Cambio] Ahora para mostrar que un usuario fue baneado, se imprime un texto y no se recurre a una plantilla.-
- [Cambio] Ahora no aparecen m&aacute;s mensajes de confirmaci&oacute;n al editar, crear o borrar una nota.-
- [Cambio] Ahora las notas nuevamente se abren en un pop-up.-
',

'2.3' => ' 30/05/09

- Corregido un bug que no permit&iacute;a cambiar el rango a los miembros dentro de las alianzas.-
- Varios cambios en el panel del admin:

-------- Integrados todos los archivos de lenguaje.-
-------- Renombrado el archivo para el reset del universo.-
-------- Unos cuantos cambios en mats.php, tanto de estilos como limpieza de c&oacute;digo.-
-------- Cambios en el left-menu del panel de administraci&oacute;n.-
-------- Renombrados algunos archivos y realizadas algunas correciones menores.-
-------- Unos cuantos ajustes en los textos y plantillas de varios archivos.-
-------- Mensaje de confirmaci&oacute;n al intentar eliminar a un jugador del servidor.-
-------- El archivo mats.php ahora se llama adminresources.php, ademas se le integro todos los archivos que manejaban las tecnolog&iacute;as y recursos.-
-------- Solucionado el bug de la actualizaci&oacute;n de puntos.-
-------- Nueva funci&oacute;n (optimizar tablas) (By Saint).-
-------- Corregido un bug que al crear una luna no le asignaba el nombre seleccionado.-
-------- Nueva funci&oacute;n (eliminar luna) (By tonique).-
-------- Corregido un bug que en la lista de lunas no mostraba el id real de la luna.-
-------- Si el usuario no tiene nivel 1 al menos no podr&aacute; ver nada del panel de administraci&oacute;n.-
-------- Optimizadas algunas querys en messall.php
-------- Eliminado el QueryExecuter.php

- Se finaliz&aacute; el soporte a las versiones v1.4d/v1.4e/v1.4f y tambi&eacute;n el auto-update de las mismas.-
- Eliminadas algunas querys innecesarias de MissionCaseMIP.php.-
- Se elimino una query innecesario de MissionCaseRecycling.php.-
- Limpieza de la base de datos de campos innecesarios dentro de la tabla users.-
- Algunos cambios y mejoras en el update.php.-
- Ahora s&oacute;lo sumar&aacute;n puntos de guerrero los jugadores que realicen batallas y no ambos.-
- Reparado un bug que mostraba el mensaje de la flota al finalizar la misi&oacute;n.-
- La experiencia del minero ya no se muestra m&aacute;s con decimales.-
- Se volvi&oacute; al antiguo dise&ntilde;o de la construcci&oacute;n de edificios.-
- Corregido un bug que en al finalizar una construcci&oacute;n en el overview aparec&iacute;a Construcci&oacute;n() en vez de Libre.-
- Algunas modificaciones en los cr&eacute;ditos.-
- Modificados algunos aspectos en el aspecto interno del juego.-
- Cambiada la imagen dentro del juego.-
- Redise&ntilde;ado el index, espero que les guste.-
- Agregado un favicon, cada uno podr&aacute; cambiarlo por el que quiera.-
- Eliminados algunos residuos provenientes de otras versiones.-
- Eliminados los frames, ahora el menu se muestra con la funci&oacute;n ShowLeftMenu.php y algo de estilos css para ajustar todo.-
- Cambiados todos los $xnova_root_path por $xgp_root.-
',

'2.2' => ' 06/05/09

- Se reemplazo el men&uacute; derecho e izquierdo por el original, y la imagen del fondo tambi&eacute;n.-
- Revisado todo messages.php,se eliminaron querys innecesarias, se organizo el c&oacute;digo, se restringieron algunas querys para optimizar la p&aacute;gina, se elimiaron elementos sin utilidad y se integro el lenguaje.-
- Toda la galaxia fue revisada, se hicieron algunas correciones en los textos y algunas mejoras visuales.-
- Corregido un bug que impedia cambiar la cantidad de planetas, sistemas y galaxias que se pod&iacute;an utilizar en el universo (modificable desde constantes.php), recomiendo dejarlo en 9-499-15, asi no saturan mucho el juego.-
- Renombrado functions.php a funciones.php
- Limpieza y revisado de constants.php, renombrado a constantes.php.-
- Corregido un bug en la p&aacute;gina de tecnolog&iacute;as.-

- Eliminadas las vars de los mensajes, no ten&iacute;an utilidad.-
- Se revisaron nuevamente todas las funciones y fueron nuevamente reasignadas utilizando el sistema de funciones_A y funciones_B.-
- Solucionado el bug de las p&aacute;ginas en blanco, dejando la versi&oacute;n bastante estable [BETA].-
- Nueva forma de distribuir las funciones, en A y B. Para mas detalles lean la informaci&oacute;n que deje comentada en commons.php, esto es provisorio, aunque creo que es la mejor forma de agilizar el juego, y tenerlo m&aacute;s estable.-
- Peque&ntilde;o cambio en el ingreso, eliminando algunas lineas.-
- Algunos cambios en el commons.php y eliminadas algunas cosas innecesarias.-
- Corregido un peque&ntilde;o bug en las listas de lunas.-
- Corregido un bug en la opci&oacute;n de crear lunas.-
',

'2.1' => ' 02/05/09

- Peque&ntilde;o cambio en el index y la selecci&oacute;n de la p&aacute;gina.-
- Incluido reg.mo directamente a reg_form.tpl y reg.php.-
- Modificado el dise&ntilde;o del registro y de la p&aacute;gina de clave perdida.-
- Correcciones gr&aacute;ficas en el index.-
- SACS funcionando al 50% [problemas en la coordinaci&oacute;n de los tiempos y en la visi&oacute;n de los movimientos de flotas].-
- Cambio visual en las estad&iacute;sticas, ahora el *, +1 y -1 (rankplus), se muestra con js(overlib).-
- Limpieza en commons.php con lo que durante el movimiento de flotas reducira la carga del juego.-
- Ahora al realizar un espionaje ya no aparecer&aacute; la p&aacute;gina en blanco ni tampoco tirara error.-
- Corregidos unos cuantos bugs provocados por la distribuci&oacute;n de las funciones.-
- Nuevas im&aacute;genes de planetas, mucho m&aacute;s vistosas.-
- Corregido un peque&ntilde;o bug que no permit&iacute;a ver las p&aacute;ginas publicas(contact.php, reg.php, credit.php y la secci&oacute;n de clave perdida).-
',

'2.0' => ' 23/04/09

- Visi&oacute;n general del panel del admin mejorada, ajustada mejor la tabla e integrado el lenguaje a las plantillas.-
- Simplificaci&oacute;n del sistema de cr&eacute;ditos, e integraci&oacute;n del lenguaje.-
- Integrado el idioma a resources.php y a las respectivas plantillas.-
- Cambios en las tablas de las estad&iacute;sticas, incluci&oacute;n del lenguaje a las plantillas, revisi&oacute;n del c&oacute;digo y algunas mejoras en la carga.-
- Algunos cambios visuales en fleet.php.-
- Eliminada la funci&oacute;n AdminMessage, cumplia la misma funci&oacute;n que message.-
- Optimizaci&oacute;n e integraci&oacute;n del lenguaje a admin/settings.php
- Algunas correcciones que previenen que por la actualizaci&oacute;n de puntos se provoque un bug en el panel de administraci&oacute;n.-
- Optimizadas algunas querys de las flotas en commons.php.-
- Correciones en algunos textos en los mensajes de movimientos de flotas.-
- Mejora de seguridad, no podr&aacute;s ver las p&aacute;ginas internas del juego sino te logueaste.-
- Ahora se pueden ver bien los mensajes de error e informes de los mensajes.-
- Corregido un bug que al abandonar un planeta no borraba la luna, y esta pod&iacute;a ser utilizada.-
- Simplificaci&oacute;n y reorganizaci&oacute;n de BatimentBuildingPage.php.-
- Correcciones visuales en los edificios, y correciones de algunas tablas para ajustarlas mejor.-
- Nueva imagen de materia oscura en el men&uacute; superior, tambi&eacute;n se ampliaron los tama&ntilde;os de las im&aacute;genes.-
- Reparado un bug que permit&iacute;a mover flotas en modo vacaciones.-
- Traducido el mensaje del modo vacaciones, y corregido un bug que no mostraba el tiempo real de vacaciones.-
- Cambiados algunos $ugamela por $xnova.-
- Implementaci&oacute;n de seguridad, fue renombrado el archivo extension.inc a extension.inc.php, no estaba protegido y pod&iacute;a leerse su contenido.-
- Algunas correcciones y simplificaci&oacute;n del c&oacute;digo en buddy.php.-
- Revisado todo el notes.php:

--------- Plantillas agregadas a su carpeta correspondiente "notes".-
--------- Algunas correciones visuales.-
--------- Integraci&oacute;n del idioma a las plantillas.-
--------- Reparados algunos bugs.-
--------- Ahora al editar el mensaje, se muestra el asunto y el mensaje.-
--------- Conteo de caracteres en js aplicado.-

- Eliminadas algunas funciones de administraci&oacute;n.-
- Reparado un bug que no mostraba el l&iacute;mite real de las flotas posibles a enviar.-
- Oficiales:

--------- Algunas correciones visuales.-
--------- Oficiales pendientes por reparar: Almirante y General.-
--------- Oficiales funcionando: Ge&oacute;logo, Ingeniero, Tecn&oacute;crata, Constructor, Cient&iacute;fico, Almacenista, Defensor, Bunker, Esp&iacute;a, Comandante, Destructor, Raider y Emperador.-
--------- Reparados los oficiales esp&iacute;a y Comandante.-
--------- Reparado el oficial empeador(By thyphoon) y destructor(By angelus_ira).-
--------- Integraci&oacute;n del idioma a las plantillas y c&oacute;digo.-

- Limpieza de scripts.-
- Re-organizadas todas las funciones del juego (optimizandolo incre&iacute;blemente)(cada funci&oacute;n se asigno a su archivo correspondiente).-
- Eliminado CombatEngine.php.-
- Algunas correciones en commons.php para agilizar el juego en general.-
- Limpieza y optimizaci&oacute;n del instalador.-
- La funci&oacute;n doquery fue unificada tambi&eacute;n dentro de functions.php.-
- Las funciones de unlocalised.php fueron integradas a functions.php
- Limpieza de funciones inutiles en includes/functions:

--------- Eliminado RevisionTime.php.-
--------- Eliminado SecureArrayFunction.php.-
--------- Eliminado ResetThisFuckingCheater.php.-
--------- Eliminado ElementBuildListQueue.php, el archivo ElementBuildListBox.php cumple la misma funci&oacute;n y se encuentra en uso.-

- Limpieza en functions.php,se borraron algunas funciones sin utilidad alguna.-
- Limpieza en unlocalised.php,se borraron algunas funciones sin utilidad alguna o vac&iacute;as.-
- Se reorganizaron casi todas las plantillas y se borraron algunas m&aacute;s sin utilidad (algunas pedientes a organizar).-
- Se borraron todas las plantillas de la galaxia que no ten&iacute;an utilidad(la galaxia la genera el c&oacute;digo php din&aacute;micamente).-
- Revisado todo el search.php:

--------- Borradas algunas lineas.-
--------- Reorganizado el c&oacute;digo.-
--------- Reorganizadas las plantillas en una carpeta en templates.-
--------- Se integro search.mo a las plantillas.-
--------- Se corrigi&oacute; un bug que no mostraba la alianza en la b&uacute;squeda por usuarios.-
--------- Se corrigi&oacute; un bug que no redirig&iacute;a correctamente a la vista de la alianza.-
--------- Se corrigi&oacute; un bug dentro de la alianza para poder verla desde search.php

- Revisado todo el mercader:

--------- Adherido marchand.mo a sus respectivas plantillas.-
--------- Corregidas todas las plantillas y bugs en la muestra de los recursos(no aparecen m&aacute;s en eltop).-
--------- Simplificaci&oacute;n del c&oacute;digo php, reorganizado y reprogramado lo que no funcionaba bien.-
--------- Corregidas las validaciones, admiten ceros, pero no n&uacute;meros negativos.-
--------- A&ntilde;adidas las plantillas respectivas a una carpeta en templates(para una mejor organizaci&oacute;n).-

- Cookies.mo integrado a su archivo correspondiente.-
- Algunos textos fueron colocados en system.mo, ya que hacen al caracter general del juego, y no de un sector en especifico.-
- Optimizado MissionCaseAttack.php.-
- Optimizado el overview, se elimino c&oacute;digo innecesario, se reorganiz&oacute;, se eliminaron querys que no ten&iacute;an utilidad y se integro el idioma a las plantillas.-
- Reubicados algunos archivos.-
- Limpieza de la base de datos, de cosas que no se utilizaban.-
- Reorganizado el men&uacute; de opciones, integraci&oacute;n del idioma a la plantilla y se eliminaron querys innecesarias.-
- Algunos archivos de texto fueron integrados directamente a los archivos para agilizar el juego y su velocidad.-
- Se reorganizaron algunas plantillas y se eliminaron algunas otras in&uacute;tiles.-
- Eliminados los emoticones.-
- Como siempre actualizados el auto-update y el instalador para que todo sea m&aacute;s facil.-
- Cambios en el instalador.-
- Optimizadas unas cuantas p&aacute;ginas.-
- login.php, lostpassword.php y logout.php unificados en el index.php mejorando un poco el rendimiento y organizaci&oacute;n.-
- Algunas correcciones visuales en la visi&oacute;n del imperio.-

- Revisada toda la alianza:

--------- Mejoras varias.-
--------- Mejoras en lenguajes.-

--------- Mejoras en plantillas.-
--------- Se agregaron validaciones.-
--------- Se reorganiz&oacute; el c&oacute;digo.-
--------- Se reparo el texto de las solicitudes, ahora podr&aacute;s editarla.-
--------- Todos los mensajes ahora te redirigiran.-
--------- Se corrigi&oacute; un bug en los rangos.-
--------- Se optimiz&oacute; un poco, se eliminaron lineas in&uacute;tiles y se fixearon algunos bugs.-
--------- Se repararon todos los errores encontrados en los textos y plantillas que no se mostraban, asi como cosas que no se realizaban.-

- Cuando un usuario falla al intentar el login ahora es redirigido al inicio.-
- Mejorado el index ahora funciona mucho m&aacute;s r&aacute;pido.-
- Mejorados algunos textos en general, y corregidos algunos detalles.-
- Redise&ntilde;ado el sistema de ingreso al panel del admin y regreso al juego.-
- Limpieza de archivos y residuos.-
- Eliminado el chat, loteria, razas, simulador, tutoria, records y todo aquello que no consideraba necesario.-
- Reprogramados los men&uacute;s derechos e izquierdos.-

- Un resumen de las figuras m&aacute;s destacadas de este proyecto:

--------- Tomo las riendas sobre la 1.5b saltando a la 2.0 para trerles todas las mejoras enunciadas a continuaci&oacute;n [By lucky].-
--------- Partiendo de la version 0.9a llegando hasta la 1.5b del XG Proyect por lucky, PowerMaster, Calzon, Tarta, Tonique y muchas personas m&aacute;s.-
--------- Continuado por UGamela Britania con varias mejoras, seguido por el equipo franc&eacute;s Raito, Chlorel, e-Zobar y Flousedid.-
--------- Proyecto ogame para todos y con todas las funciones iniciado por Perberos.-
',

'1.5b' => ' 03/04/09

- Cambios y correcciones en templates y textos.-
- Loteria reparada (By lucky).-
- Correciones en el instalador, soportando correctamente las razas, y tambi&eacute;n en el auto-update.-
- Razas corregidas (By Tonique).-

- Corregido un bug en el instalador.-
',

'1.5a' => ' 26/03/09


- Corregido el link de administraci&oacute;n.-
- Mejoras en el instalador.-
- Fix corregido bug que mostraba mal la leyenda en la galaxia.-
- Actualizado el auto-update para poder pasar f&aacute;cilmente de la versi&oacute;n 1.4f o de la 1.4c a la 1.5a.-
- Ahora la instalacion incluye la loter&iacute;a y el chat, no deber&aacute;s hacer nada manualmente.-
- Arreglada la p&aacute;gina de amigos ahora deber&iacute;a mostrar bien a tus amigos y no a vos (By lucky).-
- Mejorado el auto-update de puntos, ahora podr&aacute;s instalar sin realizar modificaciones en los archivos.-
- Unificamos la versi&oacute;n de XG Proyect con la de calzon.-
',

'1.4f' => ' 18/03/09

- Fix peque&ntilde;as correciones en la base de datos.-
- Fix peque&ntilde;as correcciones en traducciones generales.-
- Fix Corregidas variables en alianza, nueva estructuracion, mejor optimizada.-
- Mod Agregado terraformer y super terraformer a constants.php, (personalizable campos que dara cada uno).-
- Mod Administradores u operadores no aparecen mas Estadisticas.-
- Mod Completadas algunas imagenes faltantes en el skin, cambiada la de la supernova por una de mejor calidad.-
- Mod Optimizacion de consultas y variables generales (sistema mas limpio).-
- Mod Nuevo edificio, Super Terraformer, aumenta 10 campos por nivel (winjet).-
<font color="red">- Tecnologias y naves unicas de razas. 70% completado.-</font>
<font color="red">- Formas de Gobierno (democracia, socialismo y pirateria) 30% completado.-</font>
<font color="red">- Fix a bug destruccion de luna.-</font>
',

'1.4e' => ' 12/03/09

- Fix a textos e imagenes de naves y defensas nuevas asi como a razas.-
- Fix Enviar mutiples flotas, expediciones, misiones, al ir atras (modo test por ahora).-
- Fix Corregido bug en consumo de deuterio (flotenajax.php).-
- Fix corregido bug al abandonar colonias por fallo seguridad (overview.php).-
- Fix En Estadisticas aparecias en una alianza aunque ya hubieras salido (alliance.php).-
- Mod 4 Nuevas naves: Interceptor, Cazador Crucero, Transportador y Titan.-
- Mod 2 Nuevas defensas: Ca&ntilde;on de Fotones y Base Espacial.-
- Mod Nueva Tecnologia de Desarrollo, aumenta colas posibles a edificios.
<font color="green">- Mod Razas: Humanos, Aliens, Predators y Darks, con cada nivel aumenta:.-</font>
<font color="green">- Humanos: Mina Metal +3% produccion, +2% Ataque y Escudos.-</font>
<font color="green">- Aliens: Mina Cristal +3% produccion, +3% Blindaje.-</font>
<font color="green">- Predators: +10% Ataque.-</font>
<font color="green">- Darks: Sintetizador Deuterio +3% produccion, +4% Blindaje y Escudos.-</font>
',

'1.4d' => ' 09/03/09

- Fix algunas traducciones.-
- Fix Ajustado a resolucion 1024x768, reacomodo en columnas de edificios y frames.php-
- Fix multiplicacion/Duplicacion de ligeros y estrellas de la muerte (flotten1.php).-
- Fix Seguridad de carpetas, una mas, aparte de la que ya existia.-
- Fix en Mercader, devolvia recursos al meter numeros negativos (marchand.php).-
- Fix Misiles (projectxnova) adaptado y corregido a esta version (MissionCaseMIP.php).-
- Fix agregado entero en funcion investigaciones (ResearchBuildingPage.php).-
- Fix, peque&ntilde;a correccion en alianzas rangos y administracion(alliance.php).-
- Fix, Correccion en Galaxia (galaxy.php).-
- Mod/Fix Arreglo a mensajes(project xnova) adaptado, corregido y aumentado para esta version.-
- Mod actualizacion automatica (ahora si es automatica) y no consume recursos.-
- Mod Edificios en columnas de 5.-
- Mod Menu Derecho agregada compatibilidad, reordenadas las funciones.-
- Mod Agregado Records (Records.php).-
- Mod Agregado Chat.-
- Mod Agregado Simulador de Batallas.-
- Mod Agregado Loteria (project xnova), adaptado y corregido a esta version.-
- Mod Reacomodo vision general (projectxnova), corregida compatilidad (overview.php).-
- Mod Recursos en tiempo real (tonique) modo test por ahora.-
- Borrado actualizacion automatica, consume muchos recursos (todos haciendo click a vision general).-
',

'1.4c' => ' 08/02/09

- Eliminados los recursos en tiempo real debido a que se quedaban congelados.-
- Reparados los oficiales esp&iacute;a y comandante.- (By jtsamper foro project.xnova.es)
- En la galaxia ya no puedes reciclar o espiar sin deuterio.-
- Prevenir n&uacute;meros negativos y car&aacute;cteres no num&eacute;ricos en la galaxia (By neurus foro Xproject.xnova.es).-
- Ahora para ver la galaxia necesitas deuterio (Original project.xnova.es fixeado por lucky).-
- Al disolver una alianza esta ya no aparece en las estad&iacute;sticas (By xesar foro project.xnova.es).-
- Corregida una redirecci&oacute;n que funcionaba mal en la alianza.-
- Corregido un peque&ntilde;o error de sintaxis en la flota que tiraba severos reportes de errores (Gracias edering).-
- Agregado un mensaje recordatorio de como se debe incrementar o eliminar la materia oscura (Gracias edering).-
- Anuncios eliminados (Por votaci&oacute;n de los usuarios de XG).-
- El auto-update no soporta m&aacute;s las siguientes versiones:  v0.9a/v1.0a/v1.0b/v1.1a/v1.1b/v1.1c/v1.2a/v1.2b/v1.2c/v1.3a (Si tienes alguna des estas versiones deber&aacute;s usar un update anterior).-
- Ahora en la busqueda al hacer click en el link te redirecciona al sistema del jugador y no al tuyo (By Anghelito).-
',

'1.4b' => ' 06/12/08

- Desbaneo reparado.-
- Oficiales reparados.-
- Ahora al iniciar sesi&oacute;n con tu cuenta, iniciar&aacute; siempre desde el planeta principal y no desde una colonia.-
- Un moderador u operador ya no podr&aacute; cambiarse los permisos a Administrador.-
- Galaxia optimizada.-
- Ahora cuando colonizas tu planeta se llamar&aacute; "Colonia" y no "Planeta Principal" (By lucky).-
- El auto-update no soporta m&aacute;s las siguientes versiones:  v1.0a/v1.0b/v1.1a/v1.1b/v1.1c/v1.2a/v1.2b/v1.2c/v1.3a (Si tienes alguna des estas versiones deber&aacute;s usar un update anterior).-
- Corregidas algunas redirecciones y mejoradas otras.-
- Ahora puedes usar espacios en blanco en el nombre de tu planeta (By lucky).-
- Borrado de archivos innecesarios (esto no termina m&aacute;s).-
- Reparada la tabla que muestra las flotas en vuelo en el panel del admin.-
- Mejoras, organizaci&oacute;n, limpieza y optimizaci&oacute;n del lenguaje (No pongo m&aacute;s que cambie en los lenguajes porque ya es detallar mucho, para nada).-
',

'1.4a' => ' 06/12/08

- Reparado el reset del universo.-
- El auto-update no soporta m&aacute;s las siguientes versiones: v1.0a/v1.0b/v1.1a/v1.1b/v1.1c/v1.2a (Si tienes alguna des estas versiones deber&aacute;s usar un update anterior).-
- M&aacute;s limpieza de archivos innecesarios.-
- Limpieza y pulido del panel de admin (lenguaje).-
- Lista de planetas <-> Lista de usuarios cambiado (lenguaje - Gracias Alberto14).-
- Ahora puedes agregar y remover materia oscura desde el panel de administraci&oacute;n (By lucky).-
- Actualizaci&oacute;n en tiempo real de los recursos (By Alberto14).-
- Cambidas las im&aacute;genes del XNova, por las im&aacute;genes del OGame original.-
- Borradas im&aacute;genes innecesarias.-
- Optimizadas las im&aacute;genes de los oficiales.-
- Eliminado el multi totalmente (A pedido del p&uacute;blico).-
- Eliminados los records totalmente (A pedido del p&uacute;blico).-
- Eliminado el chat totalmente (A pedido del p&uacute;blico).-
- Traducidos algunos textos en el formulario de env&iacute;o de mensajes (lenguaje).-
- Complementado el infos.mo con los datos del verdadero OGame (lenguaje).-
- Pulido y limpieza del search (lenguaje).-
- Pulido y limpieza del overview (lenguaje).-
- Pulido y limpieza del leftmenu (lenguaje).-
- Pulido y limpieza del registro (lenguaje).-
- Pulido y limpieza del login (lenguaje).-
- Cambios de lenguaje en notes.-
- Cambios en el login Contact -> Contacto y Forum -> Foros.-
- Eliminado player.mo - no ten&iacute;a ninguna utilidad.-
- Limpieza del archivo de lenguaje login.-
- Reemplazados todos los "Titanio", "Silicio" y "Gashofa" por "Metal", "Cristal" y "Deuterio".-
- Correciones de lenguaje en el install y limpieza de dicho archivo (Gracias Alberto14).-
',

'1.3c DMV' => ' 30/11/08 "DMV = Dark Matter Version Exclusivo Xtreme-gameZ.com.ar"

- Correciones en los lenguajes de la supernova o super nave de batalla y el protector planetario (algo siempre me olvido).-
- Modificaci&oacute;n de la ubicaci&oacute;n de algunos arhcivos.-
- Eliminada una carpeta llamada .svn a la cual no le encontre utilidad.-
- Limpieza de archivos innecesarios y duplicados.-
- Implementada la materia oscura (C&oacute;digo 100% x lucky) (Gracias Reyndio por la idea).-
----- Los oficiales ahora se manejan por la materia oscura 1 punto oficial = 1000 materia oscura.-
----- En las expediciones se obtiene la materia oscura necesaria.-
----- No existen m&aacute;s los puntos de oficiales, aun asi se sube el nivel de minero y flota.-
----- Se siguen mostrando los registros de ataque.-
----- Auto-Update actualizado especialmente para soportar la materia oscura.-

- Ya no se pueden atacar lunas + fuerte o + debiles que uno (By Neurus).-
- Panel del admin, "Utilisateur?" -> "&iquest;Usuario?", modificaci&oacute;n en el lenguaje.-
- Por razones de seguridad elimine el phpinfo.-
- Panel del admin, "Lista de Usuarios" -> "Lista de Planetas", modificaci&oacute;n en el lenguaje (Gracias Alberto14).-
- Solucionado el error en el orden por id de la alianza (By tarta).-
',


'1.3b EU' => ' 30/11/08

- No hace falta m&aacute;s ingresar el nombre del planeta, por defecto es "Planeta Principal".-
- Eliminadas imagenes del "sexo".-
- Optimizada la imagen del inicio, ahora carga m&aacute;s r&aacute;pido.-
- Compatibilidad del auto-update con todas las versiones.-
- Nueva versi&oacute;n del auto update, m&aacute;s comprensible(creo).-
- Reparado el problema con la instalaci&oacute;n (Gracias Anghelito).-
',

'1.3a' => ' 29/11/08

- XNova 100% TRADUCIDO AL ESPA&ntilde;OL [PUDE HABERME SALTEADO ALGO POR FAVOR AVISAR](By lucky).-
- Limpieza de scripts, eliminamos varios archivos de la carpeta scrips que notamos no necesarios.-
- Reparada la validaci&oacute;n del index, ahora si la carpeta install existe no podras acceder al juego (By lucky).-
- Arreglado el modo vacaciones, ya no puedes entrar en vacaciones cuando estas atacando (By lucky).-
- No se muestran m&aacute;s los recursos negativos.-
- Redirecci&oacute;n luego de enviar una flota (By tarta).-
- Ahora los d&iacute;as se muestran con una "d" y no con una "j" (By tarta).-
- Nuevamente agregamos los emoticons.-
- Ahora puedes cambiar el nombre en el juego, por fin solucionamos esto.-
- Nuevo dise&ntilde;o del auto-update, mucho mas vistoso y atractivo.-
- Reparada la instalaci&oacute;n, ahora funcionan los misiles al instalar el juego.-
',

'1.2c EU' => ' 26/11/08


- Reparada la instalaci&oacute;n.-
',

'1.2b' => ' 26/11/08

- Misiles finalmente funcionando (By lucky).-
- Desbaneo autom&aacute;tico (By Anghelito).-
- Reparado el modo vacaciones.-
- Traducciones en varios archivos (By edering).-
- Reparado el modo debug (By tarta).-
- Reparado el link de las notas (By lucky).-
- Eliminada la carpeta emoticones.-
- Fix ranking de flotas en vuelo (By Pada).-
- Mejoras en archivos de lenguaje.-
- Cambios en el mensaje de bienvenida.-
- Records reparados.- (By tarta).-
- Actualizado el auto-update para poder actualizar: 0.9a -> 1.2a / 1.1b -> 1.2a / 1.2a -> 1.2b (By lucky).-
- Cambios en el instalador.-
- Se elimino una tabla que no hacia falta.-
',

'1.2a' => ' 19/11/08

- Actualizado el auto-update para poder actualizar: 0.9a -> 1.2a y 1.1b -> 1.2a .-
- Reorganizaci&oacute;n, recodificaci&oacute;n y reestructuraci&oacute;n de los misiles interplanetarios, ademas de solucionar seberos bugs.-
- Solo se permiten caracteres alfanumericos en el nombre de los planetas, evita serios bugs y filtros de seguridad.-
- Arreglado el orden por puntos en la alianza.-
- Tutorial funcionando.-
- Correcciones en el mensaje de bienvenida pos-registro.-
- Solucionado el bug que no permit&iacute;a la transferencia de la alianza.-
- Solucionado el bug que hace que salga el rango equivocado al usuario en la lista de miembros de la ally.-
- Solucionado el bug que permit&iacute;a que se envien solicitudes una vez que la alianza habia sido borrada.-
- Reparada la red de investigaci&oacute;n intergal&aacute;ctica.-
- Cupula y protector planetario funcionando, y cada una solo puede ser edificada una vez.-
',

'1.1c' => ' 19/11/08

- Cambios en la organizaci&oacute;n de la carpeta templates.-
- Algunos fixes en el leftmenu del admin.-
- Nuevamente reparada la seccion de de Annonces (sirve para comerciar).-
- Volvimos a implementar el leftmenu antigüo, funciona m&aacute;s r&aacute;pido.-
- Mejoras en algunas traducciones, y a&ntilde;adidas otras.-
- A&ntilde;adida la hora al chat. [A&uacute;n no funciona en hostings].-
- Limpieza de archivos inecesarios y/o sin ninguna utilidad.-
- A&ntilde;adido el auto-update.-
- Eliminado el upgrade desde ugamela.-
- Mejoras en la instalaci&oacute;n.-
',


'1.1b' => ' 30/10/08

- A&ntilde;adido un tutorial, desarrollado por PowerMaster para el XNova de Xtreme-gameZ.com.ar.-
- Cambios de nombre del archivo de instalacion "Installeur" a "Instalacion de XNova".-
- Cambios en el leftmenu para usuarios.-
- Actualizacion de Puntos Automaticamente, ahora si anda.-
- Introduccion del Release de Xtreme-GameZ en "credit.php" e "install.php".-
- Cambios de idioma de carpeta "fr" a carpeta "es" (requiere instalacion).-
',

'1.1a' => ' 28/10/08

- Antes, si mandaban una flota y cambian de planeta, tiraba error.-
- Antes, cuando estaban leyendo mensajes y cambian de planeta, tiraba error.-
- Ahora al cancelar una investigaci&oacute;n te devuelve los recursos.-
- Cambio en el texto del primer mensaje recibido al registrarse en el juego.-
- Agregadas las estad&iacute;sticas de batalla.-
- Fueron agregadas las defensas al ranking de la Visi&oacute;n General.-
',


'1.0b' => ' 26/10/08

- Primer release disponible para los usuarios.-
- Eliminado el warning que aparec&iacute;a en la instalaci&oacute;n del sistema.-
- El instalador ahora incluye la actualizaci&oacute;n de puntos autom&aacute;tica, por ende el usuario ya no debera tocar nada en el c&oacute;digo.-
- Aplicada la actualizaci&oacute;n autom&aacute;tica de puntos.-
',


'1.0a' => ' 24/10/08 "Versi&oacute;n Inicial"

- Cambios de lenguaje en el changelog (100% traducido).-
- Mejora del men&uacute; de la izquierda se "visualiza" algo mejor.-
- Correciones de lenguaje en el install (install.mo).-
- Correciones en el Marchand (Mercader), ya esta funcionando correctamente, no tira m&aacute;s ese error del lenguaje.-
- Fixes en el link de Annonces, ahora esta funcionando, ya puedes publicar lo que desees comercias.-
- Inicio del proyecto XG (XG Proyect) basandonos en el pack hecho por XNova versi&oacute;n 0.9a.-
',
);
?>