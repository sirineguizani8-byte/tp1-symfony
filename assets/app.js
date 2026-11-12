import './stimulus_bootstrap.js';
import './styles/app.css';

// Import SweetAlert2
import Swal from 'sweetalert2';

// Test SweetAlert2
Swal.fire({
    title: 'Bienvenue !',
    text: 'SweetAlert2 fonctionne correctement dans Symfony.',
    icon: 'success',
    confirmButtonText: 'OK'
});

console.log('SweetAlert2 fonctionne correctement ! 🎉');