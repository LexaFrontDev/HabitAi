import React from 'react';
import clsx from 'clsx';

type TextVariant = 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6' | 'p' | 'span';
type TextAlign = 'left' | 'center' | 'right';
type TextWeight = 'normal' | 'bold' | 'semibold' | 'light';
type TextFont = 'klein' | 'sans' | 'serif' | 'mono' | 'poppins';

interface TextBlockProps {
    children: React.ReactNode;
    variant?: TextVariant;
    align?: TextAlign;
    weight?: TextWeight;
    size?: string;
    color?: string;
    font?: TextFont;
    className?: string;
}

export const TextBlock: React.FC<TextBlockProps> = ({
                                                        children,
                                                        variant = 'p',
                                                        align = 'left',
                                                        weight = 'normal',
                                                        size = '',
                                                        color = '',
                                                        font = 'sans',
                                                        className,
                                                    }) => {
    const Tag = variant;

    const alignClass = {
        left: 'text-start',
        center: 'text-center',
        right: 'text-end',
    }[align];

    const weightClass = {
        normal: 'font-normal',
        bold: 'font-bold',
        semibold: 'font-semibold',
        light: 'font-light',
    }[weight];

    const fontClass = {
        klein: 'font-Klein',
        sans: 'font-sans',
        serif: 'font-serif',
        mono: 'font-mono',
        poppins: 'font-Poppins'
    }[font];

    return (
        <Tag className={clsx(alignClass, weightClass, fontClass, size, color, className)}>
            {children}
        </Tag>
    );
};
