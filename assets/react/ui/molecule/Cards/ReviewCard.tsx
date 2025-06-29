import React from 'react';

interface ReviewCardProps {
    comment: string;
    name: string;
}

const ReviewCard: React.FC<ReviewCardProps> = ({ comment, name }) => (
    <div className="card p-4 h-100 shadow-sm border-0 rounded-4">
        <blockquote className="blockquote mb-0">
            <p className="mb-3 fs-5 text-dark">
                <span className="text-muted fs-3 me-1">“</span>
                {comment}
                <span className="text-muted fs-3 ms-1">”</span>
            </p>
            <footer className="blockquote-footer text-muted">{name}</footer>
        </blockquote>
    </div>
);

export default ReviewCard;
