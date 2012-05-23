<form action="" name="reg_form" method="post">
    <div id="main">
        <div id="login">
            <div id="login_input">
            	<center><h3><strong>{server_message_reg} {servername}!</strong></h3></center>
            </div>
        </div>
        <div id="mainmenu" style="margin-top: 20px;">
            <a href="index.php">{index}</a>
            <a href="reg.php">{register}</a>
            <a href="{forum_url}" target="_blank">{forum}</a>
        </div>
        <div id="rightmenu" class="rightmenu">
            <div id="title">{register_at_reg} {servername}</div>
            <div id="content">
                <center>
                    <div id="text1">
                    	<table>
                        	<tr>
                            	<td>{user_reg}:</td>
                                <td><input name="character" size="20" maxlength="20" type="text"></td>
                            </tr>
                        	<tr>
                            	<td>{pass_reg}:</td>
                                <td><input name="passwrd" size="20" maxlength="20" type="password" autocomplete="off"></td>
                            </tr>
                        	<tr>
                            	<td>{email_reg}:</td>
                                <td><input name="email" size="20" maxlength="40" type="text"></td>
                            </tr>
                        </table>
                    </div>
            		<div id="register" class="bigbutton" onclick="document.reg_form.submit();">{register_now}</div>
            		<div id="text2">
                		<div id="text3">
							<center><b>{accept_terms_and_conditions} <input name="rgt" type="checkbox"></b></center>
               			 </div>
                         <!-- PLEASE DO NOT REMOVE THE COPYRGHT LINE // POR FAVOR NO BORRES LA LINEA DE COPYRIGHTS -->
                         <div id="copyright">
                    	Powered by <a href="http://www.xgproyect.net/" target="_blanck" title="XG Proyect {version}">XG Proyect</a> &#169; 2008 - {year}.
                    	</div>
                        <!-- PLEASE DO NOT REMOVE THE COPYRGHT LINE // POR FAVOR NO BORRES LA LINEA DE COPYRIGHTS -->
            		</div>
                </center>
			</div>
		</div>
	</div>
</form>