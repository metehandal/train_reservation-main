<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Train;
use App\Models\Vagon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        return Train::with('vagons')->get();
    }

    public function show($name)
    {
        return Train::with('vagons')->where('adi', $name)->first();
    }

    public function update(Request $request, $name)
    {
        $sonuc                                  = array('RezervasyonYapilabilir' => true, 'YerlesimAyrinti' => array());
        $train                                  = Train::with('vagons')->where('adi', $name)->first();
        $rezervasyon_farkli_vagona_yerlesebilir = $request->input('KisilerFarkliVagonlaraYerlestirilebilir');
        $rezervasyon_vagon_id                   = $request->input('RezervasyonYapilacakVagonID');
        $rezervasyon_kisi_sayisi                = $request->input('RezervasyonYapilacakKisiSayisi');
        $rezervasyon_ayni_vagonda_olacak        = $request->input('KisilerAyniVagondaOlacak');

        if ($rezervasyon_farkli_vagona_yerlesebilir !== null) {
            if ($rezervasyon_kisi_sayisi !== null && $rezervasyon_kisi_sayisi >= 1) {
                if ($rezervasyon_farkli_vagona_yerlesebilir == 0) {
                    if ($train->vagons->count() > 0) {
                        if ($rezervasyon_vagon_id !== null) {
                            $vagon = $train->vagons->where('id', $rezervasyon_vagon_id)->where('train_id', $train->id)->first();

                            if ($vagon == null) {
                                return "Girdiğiniz '$rezervasyon_vagon_id' ID numaralı vagon seçili trene ait değildir!";
                            }

                            if ($vagon->doluluk_yuzdesi >= 70) {
                                $sonuc['RezervasyonYapilabilir'] = false;
                                return $sonuc;
                            }

                            if ($rezervasyon_kisi_sayisi == 1 && $train->vagons->count() == 1) {
                                return '"RezervasyonYapilacakKisiSayisi" değeri 1 girildiğinde "KisilerAyniVagondaOlacak" değeri 0 girilemez!';
                            }

                            if ($rezervasyon_ayni_vagonda_olacak == 0 && $train->vagons->count() == 1) {
                                return 'Seçili trene ait 1 adet vagon bulunduğundan dolayı "KisilerAyniVagondaOlacak" değeri 0 girilemez!';
                            }

                            $rezervasyon              = new Reservation();
                            $rezervasyon->train_id    = $train->id;
                            $rezervasyon->vagon_id    = $vagon->id;
                            $rezervasyon->kisi_sayisi = $rezervasyon_kisi_sayisi;
                            $vagon->dolu_koltuk       = $vagon->dolu_koltuk + $rezervasyon_kisi_sayisi;
                            $sonuc['YerlesimAyrinti'] = $rezervasyon;
                            $sonuc['Vagon']           = $vagon;

                            $rezervasyon->save();
                            $vagon->save();
                            return $sonuc;
                        } else {
                            return 'Lütfen rezervasyon yapmak istediğiniz "RezervasyonYapilacakVagonID" değerini giriniz!';
                        }
                    } else {
                        $sonuc['RezervasyonYapilabilir'] = false;
                        return $sonuc;
                    }
                } else {
                    if ($rezervasyon_vagon_id == null) {
                        if ($train->vagons->count() > 1) {
                            if ($rezervasyon_ayni_vagonda_olacak == 1) {
                                if ($rezervasyon_kisi_sayisi > 1) {
                                    $vagons                       = Vagon::where('doluluk_yuzdesi', '<', 70)->where('train_id', $train->id)->get();
                                    $dagitim_yapilabilir_vagon_id = $vagons->pluck('id');
                                    $vagon_id                     = $dagitim_yapilabilir_vagon_id->random();
                                    $vagon                        = Vagon::findOrFail($vagon_id);
                                    $kisi_sayisi                  = $rezervasyon_kisi_sayisi;
                                    $vagon_sayisi                 = $dagitim_yapilabilir_vagon_id->count();

                                    if ($vagon_sayisi == 1) {
                                        return 'Seçili trende doluluk oranı %70\' den küçük 1 adet vagon bulunduğundan dolayı yolcu dağıtımı rastgele bir vagona yapılamamaktadır!';
                                    }

                                    $rezervasyon              = new Reservation();
                                    $rezervasyon->train_id    = $train->id;
                                    $rezervasyon->vagon_id    = $vagon_id;
                                    $rezervasyon->kisi_sayisi = $kisi_sayisi;
                                    $vagon->dolu_koltuk       = $vagon->dolu_koltuk + $kisi_sayisi;
                                    $vagon->doluluk_yuzdesi   = ($vagon->dolu_koltuk * 100) / $vagon->kapasite;

                                    $sonuc['YerlesimAyrinti'] = $rezervasyon;
                                    $sonuc['Vagon']           = $vagon;

                                    $rezervasyon->save();
                                    $vagon->save();

                                    return $sonuc;
                                } else {
                                    return 'Farklı vagonlara yerleşim yapabilmek için lütfen "RezervasyonYapilacakKisiSayisi" değerini 1\' den büyük integer bir değer olarak giriniz!';
                                }
                            } else {
                                $response     = array('RezervasyonYapilabilir' => true, 'YerlesimAyrinti' => array());
                                $vagons       = Vagon::where('doluluk_yuzdesi', '<', 70)->where('train_id', $train->id)->get();
                                $kisi_sayisi  = $request->input('RezervasyonYapilacakKisiSayisi');
                                $vagon_sayisi = $vagons->count();
                                $vagon_ids    = $vagons->pluck('id')->toArray();

                                if ($vagon_sayisi == 1) {
                                    return 'Seçili trende doluluk oranı %70\' den küçük 1 adet vagon bulunduğundan dolayı yolcu dağıtımı farklı vagonlara yapılamamaktadır!';
                                } elseif ($vagon_sayisi == 0) {
                                    return 'Seçili trende doluluk oranı %70\' den küçük vagon bulunmadığından dolayı yolcu dağıtımı farklı vagonlara yapılamamaktadır!';
                                }

                                function yolcuDagit($kisi_sayisi, $vagon_sayisi)
                                {
                                    $numbers = [];

                                    for ($i = 1; $i < $vagon_sayisi; $i++) {
                                        $random      = mt_rand(0, $kisi_sayisi / ($vagon_sayisi - $i));
                                        $numbers[]   = $random;
                                        $kisi_sayisi -= $random;
                                    }

                                    $numbers[] = $kisi_sayisi;
                                    shuffle($numbers);
                                    return $numbers;
                                }

                                $numbers     = yolcuDagit($kisi_sayisi, $vagon_sayisi);
                                $yerlesimler = array_combine($vagon_ids, $numbers);

                                foreach ($yerlesimler as $yerlesim => $kisiler) {
                                    $rezervasyon              = new Reservation();
                                    $rezervasyon->train_id    = $train->id;
                                    $rezervasyon->vagon_id    = $yerlesim;
                                    $rezervasyon->kisi_sayisi = $kisiler;
                                    $vagon                    = Vagon::where('id', $yerlesim)->first();
                                    $vagon->dolu_koltuk       = $vagon->dolu_koltuk + $kisiler;
                                    $vagon->doluluk_yuzdesi   = ($vagon->dolu_koltuk * 100) / $vagon->kapasite;

                                    $rezervasyon->save();
                                    $vagon->save();
                                }
                                $rezervasyon_bilgileri = Reservation::select('id', 'train_id', 'vagon_id', 'kisi_sayisi')->where('created_at', now())->get();

                                foreach ($rezervasyon_bilgileri as $bilgi) {
                                    $response['YerlesimAyrinti'] = array_push($response, $bilgi);
                                }

                                $vagons_last          = Vagon::select('id', 'train_id', 'kapasite', 'dolu_koltuk', 'doluluk_yuzdesi')->where('doluluk_yuzdesi', '<', 70)
                                                             ->where('train_id', $train->id)->get();
                                $response['Vagonlar'] = $vagons_last;
                                return $response;
                            }
                        } else {
                            return 'Seçmiş olduğunuz trende 1 adet vagon bulunduğundan veya hiç vagon bulunmadığından dolayı yolcuların farklı vagonlara yerleşimi yapılamamaktadır!';
                        }
                    } else {
                        return '"KisilerFarkliVagonlaraYerlestirilebilir" ve "RezervasyonYapilacakVagonID" değerleri aynı anda girilemez!';
                    }
                }
            } else {
                return 'Lütfen "RezervasyonYapilacakKisiSayisi" değerini 0\' dan büyük integer bir değer olarak giriniz!';
            }
        } else {
            return 'Lütfen kişilerin farklı vagonlara yerleşip yerleşemeyeceği bilgisini giriniz!';
        }
    }
}
