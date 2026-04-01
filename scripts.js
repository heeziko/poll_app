document.addEventListener('DOMContentLoaded', () => {
    const visitSourceRadios = document.querySelectorAll('input[name="visit_source"]');
    const otherSourceField = document.getElementById('other-source-field');
    const otherSourceInput = otherSourceField.querySelector('input');

    visitSourceRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (e.target.value === '기타') {
                otherSourceField.classList.remove('hidden');
                otherSourceInput.required = true;
            } else {
                otherSourceField.classList.add('hidden');
                otherSourceInput.required = false;
            }
        });
    });

    // Simple Form Validation before submit
    const pollForm = document.getElementById('poll-form');
    pollForm.addEventListener('submit', (e) => {
        const purposes = document.querySelectorAll('input[name="purpose[]"]:checked');
        if (purposes.length === 0) {
            alert('방문 목적을 하나 이상 선택해 주세요.');
            e.preventDefault();
            return;
        }
    });

    // Smooth hover effect for rating scale
    const ratingBoxes = document.querySelectorAll('.rating-box');
    ratingBoxes.forEach((box, idx) => {
        box.addEventListener('mouseenter', () => {
            for(let i=0; i<=idx; i++) {
                ratingBoxes[i].querySelector('span').style.borderColor = 'var(--primary)';
            }
        });
        box.addEventListener('mouseleave', () => {
            ratingBoxes.forEach(b => {
                if(!b.querySelector('input').checked) {
                    b.querySelector('span').style.borderColor = 'var(--border)';
                }
            });
        });
    });
});
