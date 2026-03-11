document.addEventListener("DOMContentLoaded", function() {
    const tutor = document.getElementById("tutorSection");
    const guardian = document.getElementById("guardianSection");
    // Animation timing to ensure a clean visual entrance
    setTimeout(() => {
        tutor.classList.add("animate-left-to-right");
        guardian.classList.add("animate-right-to-left");
    }, 150);
});
(function(msg, duration=2000){
    const d=document.createElement('div');
    d.textContent=msg;
    Object.assign(d.style,{
        position:'fixed',
        top:'20px',
        left:'50%',
        transform:'translateX(-50%)',
        background:'#4cff9d',
        color:'#03391b',
        padding:'8px 16px',
        borderRadius:'6px',
        fontSize:'14px',
        fontWeight:'500',
        zIndex:'9999',
        opacity:'0',
        transition:'opacity 0.3s'
    });
    document.body.appendChild(d);
    requestAnimationFrame(()=>d.style.opacity='1');
    setTimeout(()=>{ d.style.opacity='0'; setTimeout(()=>d.remove(),300); }, duration);
})("You are successfully logged in. Please select your role to continue.");