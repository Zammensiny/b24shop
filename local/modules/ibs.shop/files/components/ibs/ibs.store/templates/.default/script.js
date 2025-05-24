function rollback() {
    let path = window.location.pathname;
    if (path.endsWith('/')) {
        path = path.slice(0, -1);
    }
    const lastSlashIndex = path.lastIndexOf('/');
    if (lastSlashIndex > 0) {
        path = path.substring(0, lastSlashIndex + 1);
    } else {
        path = '/';
    }
    window.location.href = path;
}

function toSefFolder() {
    window.location.href = '/store/';
}