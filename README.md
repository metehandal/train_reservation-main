<h1>Tren rezervasyon API</h1>
<p>Laravel framework kullanılarak PHP programlama dili ile geliştirilmiş tren rezervasyon API.</p>
<h3>Kullanımı</h2>
<ul>
<li>XAMPP veya WAMPP gibi paket sunucu programları ile lokalde sunucu ve veritabanı çalıştırılır.</li>
<li>Proje dosyaları içinde bulunan <code>.env.example</code> dosyası <code>.env</code> olarak düzenlenir.</li>
<li><code>.env</code> dosyasında bulunan veritabanı konfigürasyonları kendi lokal ayarlarımıza göre değiştirilir.<em>(DB_ ile başlayan konfigürasyonlar)</em></li>
<li>Proje dizininde terminal ekranından <code>composer install</code> komutu ile composer bağımlılıkları yüklenir.</li>
<li>Proje dizininde terminal ekranından <code>php artisan key:generate</code> komutu ile yeni bir uygulama anahtarı oluşturulur.</li>
<li>Proje dizininde terminal ekranından <code>php artisan migrate:fresh --seed</code> komutu ile veritabanımıza gerekli tablolar oluşturulur ve içerisine fake veriler yazılır.</li>
<li>Proje dizininde terminal ekranından <code>php artisan route:list</code> komutu ile projede kullanılan rotalara erişilir.</li>
<li>Postman benzeri bir program ile bu rotalar aracılığıyla API kullanılabilir.</li>
<li>Örnek1 <code>http://localhost/train_reservation/public/rezervasyon</code> adresine <code>GET</code> header ile gidilirse API bize sistemde kayıtlı olan tren ve vagon bilgilerini dönecektir.</li>
<li>Örnek2 <code>http://localhost/train_reservation/public/rezervasyon/tren/dogu_ekspress</code> adresine <code>GET</code> header ile gidilirse API bize sistemde eğer kayıtlı ise <code>dogu_ekspress</code> isimli tren ve bu trene ait eğer varsa vagon bilgilerini dönecektir.</li>
<li>Örnek3 <code>http://localhost/train_reservation/public/rezervasyon//tren/dogu_ekspress</code> adresine <code>POST</code> header ile gidilirse API gerekli parametreler dahilinde <code>dogu_ekspress</code> isimli trenin eğer varsa uygun vagon ya da vagonlarına rezervasyon kayıt ya da kayıtları oluşturacaktır.</li>
</ul>
<h3>API'nin Kullandığı Parametreler ve İşlevleri</h3>
<ul>
<li><code>KisilerFarkliVagonlaraYerlestirilebilir</code> : Bu parametre API'ye rezervasyon yapılacak kişilerin rezervasyon işlemi sırasında farklı vagonlara dağıtım yapılıp yapılmayacağı bilgisini iletir. <code>boolean</code> veri tipi kullanır.(0,1) Girilmesi zorunludur.</li>
<li><code>RezervasyonYapilacakVagonID</code> : Bu parametre API'ye rezervasyonun hangi vagona yapılmak istendiği bilgisini iletir. Eğer <code>KisilerFarkliVagonlaraYerlestirilebilir</code> parametresi <code>1</code>(true) olarak girilmişse bu parametre boş bırakılmak zorundadır.</li>
<li><code>RezervasyonYapilacakKisiSayisi</code> : Bu parametre API'ye rezervasyon işleminin kaç kişi için olacağı bilgisini iletir. <code>Integer</code> bir değer girilmelidir. Girilmesi zorunludur.</li>
<li><code>KisilerAyniVagondaOlacak</code> : Bu parametre API'ye rezervasyonu yapılacak kişilerin tümünün aynı vagonda olup olmayacağı bilgisini iletir. <code>boolean</code> veri tipi kullanır.(0,1) <code>KisilerFarkliVagonlaraYerlestirilebilir</code> parametresi ile birlikte ikisi de <code>1</code> olarak girilirse API, rezervasyonu yapılacak kişilerin tümünü uygun olan vagonlardan birine rastgele olarak yerleştirir. Eğer <code>KisilerFarkliVagonlaraYerlestirilebilir</code> parametresi <code>1</code> olarak girilip <code>KisilerAyniVagondaOlacak</code> parametresi <code>0</code> girilirse rezervasyonu yapılacak kişiler uygun olan vagonlar arasında rastgele olarak değıtılır.</li>
</ul>