import React from 'react';
import clsx from 'clsx';

type TextVariant = 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6' | 'p' | 'span';
type TextAlign = 'left' | 'center' | 'right';
type TextWeight = 'normal' | 'bold' | 'semibold' | 'light';

interface TextBlockProps {
    children: React.ReactNode;
    variant?: TextVariant;
    align?: TextAlign;
    weight?: TextWeight;
    size?: string;
    color?: string;
    className?: string;
}

export const TextBlock: React.FC<TextBlockProps> = ({
                                                        children,
                                                        variant = 'p',
                                                        align = 'left',
                                                        weight = 'normal',
                                                        size = '',
                                                        color = '',
                                                        className,
                                                    }) => {
    const Tag = variant;

    const alignClass = {
        left: 'text-start',
        center: 'text-center',
        right: 'text-end',
    }[align];

    const weightClass = {
        normal: '',
        bold: 'fw-bold',
        semibold: 'fw-semibold',
        light: 'fw-light',
    }[weight];

    return (
        <Tag className={clsx(alignClass, weightClass, size, color, className)}>
            {children}
        </Tag>
    );
};
