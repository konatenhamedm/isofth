import './styles/app.scss';
import  'datatables.net'
import  'datatables.net-bs4'
import 'datatables.net-responsive-bs4'
$('#datatable').DataTable({
    "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
    },
    "select": true,

    order: [[ 1, 'asc' ]],
    responsive: true
});