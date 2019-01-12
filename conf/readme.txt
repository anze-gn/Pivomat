UPORABNIKI:
Admin:
- admin@pivomat.si/pivomat
Prodajalci:
- janez.novak@pivomat.si/geslo
- metka.dolenc@pivomat.si/geslo
- marjetka.kovac@pivomat.si/geslo
Stranke:
- urban@gmail.com/geslo
- lara.oblak@hotmail.com/geslo
- rudi.jerman@gmail.com/geslo
- toncka@hotmail.com/geslo

CERTIFIKATI:
Geslo za VSE certifikate:
- pivomatagencija
Strezniski certifikati: 
- localhost
Certifikati prodajalcev in admina:
- Janez Novak
- Metka Dolenc
- Marjetka Kovac
- admin
Nameščanje - Apache:
- sudo mkdir /etc/apache2/ssl/
- sudo cp certs/pmca.crt certs/pmca-crl.pem certs/localhost.pem /etc/apache2/ssl
- sudo a2enmod ssl
- sudo a2enmod rewrite
- sudo a2ensite default-ssl.conf
- sudo cp default-ssl.conf /etc/apache2/sites-available/
- sudo cp 000-default.conf /etc/apache2/sites-available/
- sudo service apache2 restart
Nameščanje - Firefox:
- Certificate manager -> Authorities -> Import -> pmca.crt -> Trust this CA to identify websites [check]
- Certificate manager -> Your Certificates -> Import -> Janez_Novak.p12, Marjetka_Kovac.p12, Metka_Dolenc.p12, admin.p12
   -> password: pivomatagencija

PEAR
- sudo pear install -Z HTML_QuickForm2 HTML_QuickForm2_Captcha-0.1.2
