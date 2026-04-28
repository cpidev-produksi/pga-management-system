import './bootstrap';

// 1. Import Alpine (Kode Lama Anda)
import Alpine from 'alpinejs';

// 2. Import Driver.js & CSS-nya (Kode Baru)
import { driver } from "driver.js";
import "driver.js/dist/driver.css";

// 3. Assign ke Global Window
window.Alpine = Alpine;
window.driver = driver; 

// 4. Jalankan Alpine
Alpine.start();