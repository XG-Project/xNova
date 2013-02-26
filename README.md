# Aviso a los que vengan de XG Proyect #

En principio, esta es una versión con algunos fallos corregidos de la última estable de XG
Proyect 2.x, pero no está lo suficientemente testeada como para considerarse estable. Tiene varios
cambios que podrían no ser estables, y algunos mods, como el de la gestión de errores PHP no están
acabados. Todavía hay errores que se están solucionando, y estoy trabajando junto con XG Proyect
para hacerlo lo más rápido posible.

xNova está preparada para ser actualizada desde cualquier versión de XG Proyect desde la 2.9.0,
pero solo hasta la 2.10.4. Es decir, cualquier versión superior no está soportada. En principio, se intentará
soportar toda la rama 2.10.x, y por el momento se descarta implementar la actualización desde XG Proyect 3. La diferencia entre las versiones 2.10 y 3 de XG es tal que se hace muy complejo implementar un sistema de actualización que sea capaz de controlar ese cambio. No obstante, una vez se publique XG Proyect 3, se intentará implementar un downgrade.

En un futuro, que no es esperable que sea antes de la salida de la versión 1.0.0 de xNova se pretende actualizar el xNova para que esté basado en XG, aunque no se sabe cuando será. El mayor problema ante esto es el terrible cambio del código. Es probable que en lugar de basar xNova en XG 3.x, se haga que xNova se actualice para implementar el código de XG 3, aunque no estará directamente basado en el. Es decir, que una vez salga XG 3, xNova seguirá su propio camino, y solo se soportarán las versiones de seguridad de la rama 2.10 de XG. Además, es probable que esa rama se quede fuera del soporte de xNova a partir de la versión 1.1.

# Razican's xNova #

Mi pequeño aporte al mundo de xNova, me basaré en XG Proyect 2.10.x


# Objetivos: #

El principal objetivo será el solucionar todos los posibles errores de XG Proyect, no obstante, se
pretende crear un juego renovado no solo gráficamente sino también en cuanto a su código.

# Errores: #

Hasta el momento se han encontrado más de 3.500 errores distintos que todavía no se han solucionado.
En este momento todos ellos son E_NOTICE. Se irán solucionando y encontrando nuevos. También se han
detectado errores HTML y Javascript. En principio parte de la solución llegará cuando se reescriba la
interfáz gráfica, proceso que se está llevndo acabo en la rama feature/HTML5.

# Nuevas Características: #

Ha cambiado por completo el panel de administración. Ahora usa CSS3 (compilado con LESS3) y HTML5. Se ha
añadido una variable de plantilla especial. {skin_path} tendrá la dirección http a la raiz del tema.
Obviamente no se podrán usar esas variables a la hora de enviar variables a las plantillas. En las propias plantillas siempre se podrán usar.

# Requisitos mínimos: #

Se necesita PHP 5.3.0+, APACHE 2.2+ y MySQL 4.1+. Es compatible hasta PHP 5.4, APACHE 2.4 y MySQL 5.5. Se recomienda tener la última versión actualizada en todos estos programas.

# Testing faltante #

## Instalador ##
* Actualización desde XG Proyect (2.9.0 - 2.10.4)
* Registro de la administración