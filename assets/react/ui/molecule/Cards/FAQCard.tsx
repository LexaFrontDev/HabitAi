import React from 'react';

interface FAQCardProps {
    question: string;
    answer: string;
    className?: string;
}

const FAQCard: React.FC<FAQCardProps> = ({ question, answer, className = '' }) => {
    return (
        <details className="faq-item">
            <summary className="faq-question fw-semibold">{question}</summary>
            <p className="faq-answer text-muted">{answer}</p>
        </details>
    );
};

export default FAQCard;
