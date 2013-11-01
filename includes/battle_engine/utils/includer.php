<?php
/**
 *  OPBE
 *  Copyright (C) 2013  Jstar
 *
 * This file is part of OPBE.
 *
 * OPBE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPBE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with OPBE.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OPBE
 * @author Jstar <frascafresca@gmail.com>
 * @copyright 2013 Jstar <frascafresca@gmail.com>
 * @license http://www.gnu.org/licenses/ GNU AGPLv3 License
 * @version alpha(2013-2-4)
 * @link https://github.com/jstar88/opbe
 */
require (XGP_ROOT."includes/battle_engine/utils/DeepClonable.php");
require (XGP_ROOT."includes/battle_engine/utils/Math.php");
require (XGP_ROOT."includes/battle_engine/utils/Number.php");
require (XGP_ROOT."includes/battle_engine/utils/Events.php");
require (XGP_ROOT."includes/battle_engine/models/Type.php");
require (XGP_ROOT."includes/battle_engine/models/Fighters.php");
require (XGP_ROOT."includes/battle_engine/models/Fleet.php");
require (XGP_ROOT."includes/battle_engine/models/HomeFleet.php");
include (XGP_ROOT."includes/battle_engine/models/Defense.php");
include (XGP_ROOT."includes/battle_engine/models/Ship.php");
require (XGP_ROOT."includes/battle_engine/models/Player.php");
require (XGP_ROOT."includes/battle_engine/models/PlayerGroup.php");
require (XGP_ROOT."includes/battle_engine/combatObject/Fire.php");
require (XGP_ROOT."includes/battle_engine/combatObject/PhysicShot.php");
require (XGP_ROOT."includes/battle_engine/combatObject/ShipsCleaner.php");
require (XGP_ROOT."includes/battle_engine/combatObject/FireManager.php");
require (XGP_ROOT."includes/battle_engine/core/Battle.php");
require (XGP_ROOT."includes/battle_engine/core/BattleReport.php");
require (XGP_ROOT."includes/battle_engine/core/Round.php");
require (XGP_ROOT."includes/battle_engine/constants/battle_constants.php");
?>