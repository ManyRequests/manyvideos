export function sizeToHuman (bytes) {
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes === 0) return '0 Byte';
    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10);
    if (i === 0) return `${bytes} ${sizes[i]}`;
    return `${(bytes / (1024 ** i)).toFixed(1)} ${sizes[i]}`;
};

export function timeToHuman (seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const remainingSeconds = seconds % 60;

    let final = '';

    if (hours > 0) {
        final += `${hours}h `;
    }

    if (minutes > 0) {
        final += `${minutes}m `;
    }

    if (remainingSeconds > 0) {
        final += `${remainingSeconds}s`;
    }

    return final;
};
