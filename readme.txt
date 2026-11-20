=== Anova Router ===
Contributors: lotenganova
Requires at least: 6.2
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.3
License: GPLv2 or later

Tema ringan untuk Anova Canvas. Plugin Anova Canvas diminta untuk integrasi builder bawaan Anova Router, tetapi plugin Canvas dapat digunakan dengan tema WordPress apa pun.

== Deskripsi ==
Anova Router sengaja hampir kosong (mirip Hello Elementor): tidak ada sidebar, tidak ada widget, tidak ada opsi berlebihan. Tugasnya hanya "merutekan" setiap bagian halaman ke builder yang kamu pakai. Anova Canvas merupakan plugin terpisah dan tidak mensyaratkan tema Anova Router.

== Plugin pendamping (wajib untuk integrasi bawaan) ==
Pasang dan aktifkan plugin Anova Canvas secara terpisah untuk memakai builder serta Theme Builder bawaan. Setelah tema diaktifkan, Anova Router menampilkan pemberitahuan pemasangan/aktivasi bila plugin belum tersedia. Loteng Kit bersifat opsional.

Urutan header & footer:
1. Anova Canvas → Theme Builder (header/footer dengan aturan tampil).
2. Elementor Pro → Theme Builder (semua lokasi inti didaftarkan).
3. Builder lain lewat filter `anova_router/do_location`.
4. Header & footer bawaan yang rapi (logo, menu, hak cipta) — bisa dimatikan.

Anova Canvas bisa dipakai di tema apa pun; Anova Router hanya membuat integrasi header/footer Canvas lebih bersih (tanpa output buffering header tema). Loteng Kit tidak dibutuhkan oleh Anova Router.

== Template halaman ==
* Anova Router — Kanvas kosong: tanpa header, footer dan judul.
* Anova Router — Lebar penuh: header & footer tampil, konten selebar layar (mengabaikan tata letak boxed).
* Per halaman: kotak "Sembunyikan judul halaman" di sidebar editor.

== Pengaturan ==
Tampilan → Sesuaikan → Anova Router: tata letak halaman, header/footer bawaan, judul halaman, muat CSS tema, warna aksen, teks footer.

== Tata letak halaman ==
Body selalu mendapat kelas `ar-layout-full`, `ar-layout-boxed`, atau `ar-layout-custom`.

* Full — kontainer tanpa batas lebar: header, konten, dan footer mengikuti lebar layar (gutter tetap).
* Boxed — seluruh situs dibungkus kotak berlebar tetap (pengaturan "Lebar boxed", 600–2400 px) di atas latar halaman ("Warna latar luar"). Muncul margin, bayangan, dan latar khas boxed; blok `alignfull` mengikuti lebar kotak, bukan melewatinya.
* Custom — lebar maksimum diatur sendiri (pengaturan "Lebar custom", 480–2400 px), tanpa kotak dan tanpa latar luar. Ini tata letak bawaan (1200 px) sehingga tampilan situs lama tidak berubah setelah pembaruan.

Lebar aktif dibagikan ke Anova Canvas lewat filter `anova_canvas/theme_container_width`; pada tata letak full, Canvas menerima 1920 px (bisa diubah lewat filter `anova_router/full_canvas_width`).
Template "Kanvas kosong" dan "Lebar penuh" selalu selebar layar dan tidak memakai pembungkus boxed.

== Untuk developer ==
* `anova_router_location( 'header' )` — cetak lokasi lewat builder, true bila berhasil.
* Filter `anova_router/do_location` ( $handled, $location ).
* Filter `anova_router/show_default_header`, `anova_router/show_default_footer`, `anova_router/show_page_title`.
* `anova_router_layout()` — 'full' | 'boxed' | 'custom'; filter `anova_router/layout`.
* `anova_router_container_width()` — lebar kontainer aktif (px) untuk tata letak boxed/custom.
* `anova_router_shell_open()` / `anova_router_shell_close()` — pembungkus `.ar-shell` (hanya pada boxed).
* Tema mendeklarasikan `add_theme_support( 'anova-canvas' )`.

== Changelog ==
= 1.0.3 =
* Screenshot tema di Tampilan → Tema mengikuti bahasa WordPress: Bahasa Indonesia memakai `screenshot.jpg` (bawaan), bahasa lain otomatis memakai `screenshot-en.jpg`.
* Filter `anova_router/screenshot_lang` untuk menentukan bahasa screenshot (mis. menambah `screenshot-xx.jpg`).
* Seluruh teks tema mengikuti bahasa WordPress: teks sumber kini berbahasa Inggris, terjemahan Bahasa Indonesia di `languages/id_ID.mo` (plus `id_ID.l10n.php` untuk WP 6.5+ dan template `anova-router.pot`). Mencakup deskripsi tema, nama template halaman, pemberitahuan plugin wajib Anova Canvas, pengaturan Customizer, dan teks frontend.
* Tag tema yang tidak diterjemahkan WordPress (custom-menu, threaded-comments, translation-ready) kini punya label terjemahan.
* Screenshot memakai bahasa yang sama dengan teks admin (bahasa profil pengguna, bawaannya Bahasa Situs).

= 1.0.2 =
* Tata letak halaman baru: Full, Boxed, dan Custom dengan kontrol Customizer terpisah (lebar boxed, lebar custom, warna latar luar).
* Bawaan "Custom" 1200 px mempertahankan tampilan 1.0.1; lebar kontainer lama otomatis dipakai bila belum ada pengaturan lebar custom.
* Body class `ar-layout-*`, pembungkus `.ar-shell` untuk boxed, dan penyesuaian `alignfull`/`alignwide` per tata letak.
* Template "Kanvas kosong" dan "Lebar penuh" dikecualikan dari bingkai boxed.

= 1.0.1 =
* Anova Canvas diminta sebagai plugin pendamping untuk integrasi bawaan; Loteng Kit dinyatakan opsional.
* Pemberitahuan admin menyediakan jalur pasang atau aktifkan Canvas.

= 1.0.0 =
* Rilis pertama.
