function redirectToDashboard() {
    <?php if (isset($_SESSION['id'])): ?>
    switch ("<?php echo $_SESSION['role']; ?>") {
        case 'mentor':
            window.location.href = '../../../../../../Hackentine/View/10X Mentor/mentor.php';
            break;
        case 'coordinator':
            window.location.href = '../../../../../../../Hackentine/Modules/Club Coordinator/View/core.php';
            break;
        case 'member':
            window.location.href = '../../../../../../../Hackentine/View/Student Dashboard/student.php';
            break;
        default:
            window.location.href = '../../../../../../../Hackentine/View/default.php';
    }
    <?php else: ?>
    window.location.href = '../../../../../../../Hackentine/View/default.php';
    <?php endif; ?>
}