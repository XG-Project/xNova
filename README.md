Aviso a los que vengan de XG Proyect
====================================

En principio, esta versión es una versión con algunos fallos corregidos de la última estable de XG
Proyect, pero no está lo suficientemente testeada como para considerarse estable. Tiene varios
cambios que podrían no ser estables, y algunos mods, como el de la gestión de errores PHP no están
acabados. Todavía hay errores que se están solucionando, y estoy trabajando junto con XG Proyect
para hacerlo lo más rápido posible.

Razican's xNova
===============

Mi pequeño aporte al mundo de xNova, me basaré en XG Proyect 2.10.x


Objetivos:
==========

El principal objetivo será el solucionar todos los posibles errores de XG Proyect, no obstante, se
pretende crear un juego renovado no solo gráficamente sino también en cuanto a su código.

Errores:
========

Hasta el momento se han encontrado más de 3.000 errores distintos que todavía no se han solucionado.
En este momento todos ellos son E_NOTICE. Se irán solucionando y encontrando nuevos. También se han
detectado errores HTML y Javascript. En principio parte de la solución será cuando se reescriba la
interfáz gráfica.

Nuevas Características:
=======================

Ha cambiado por completo el panel de administración. Ahora usa CSS3 (compilado con LESS3) y HTML5. Se han
añadido dos variables de plantillas especiales. {game_url} tendrá la dirección http a la raiz del juego.
{skin_path} tendrá la dirección http a la raiz del tema. Obviamente no se podrán usar esas variables a la
hora de enviar variables a las plantillas. En las propias plantillas siempre se podrán usar.

Requisitos mínimos:
===================

Se necesita PHP 5.3.0+, APACHE 2.2+ y MySQL 4.1+. Es compatible hasta PHP 5.4, APACHE 2.4 y MySQL 5.5. Se recomienda tener la última versión actualizada en todos estos programas.