document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Logic สำหรับ Sitemap Accordion เปิดแล้วเลื่อนหน้าจอ
    const mainAccordion = document.getElementById('mainSitemapAccordion');

    if (mainAccordion) {
        mainAccordion.addEventListener('toggle', () => {
            if (!mainAccordion.open) return;

            setTimeout(() => {
                const content = mainAccordion.querySelector('[data-footer-content]');
                if (content) {
                    content.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }
            }, 150); 
        });
    }

    // 2. JS สลับ Tailwind Classes เพื่อทำ Fade-in animation เมื่อเลื่อนจอ
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.05
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // เอาคลาสที่ซ่อนไว้ออก
                entry.target.classList.remove('opacity-0', 'translate-y-5');
                // ใส่คลาสที่ทำให้มองเห็นและเลื่อนกลับที่เดิม
                entry.target.classList.add('opacity-100', 'translate-y-0');
                
                observer.unobserve(entry.target); // เลิกติดตามเมื่อแสดงผลแล้ว
            }
        });
    }, observerOptions);

    // ดึง Element ทุกตัวในหน้าเว็บที่มีคลาส .js-scroll-animate มาเข้าสู่ระบบ Observer
    document.querySelectorAll('.js-scroll-animate').forEach((el) => {
        observer.observe(el);
    });

});