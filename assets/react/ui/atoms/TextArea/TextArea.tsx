import React, { useRef, useEffect } from 'react';
import clsx from 'clsx';

type Variant = 'default' | 'transparent';

interface TextAreaProps extends React.TextareaHTMLAttributes<HTMLTextAreaElement> {
    variant?: Variant;
    className?: string;
}

const TextArea: React.FC<TextAreaProps> = ({ variant = 'default', className, ...props }) => {
    const ref = useRef<HTMLTextAreaElement>(null);

    const resize = () => {
        const el = ref.current;
        if (!el) return;
        el.style.height = 'auto';
        el.style.height = `${el.scrollHeight}px`;
    };

    useEffect(() => {
        resize();
    }, [props.value]);

    return (
        <textarea
            ref={ref}
            {...props}
            onInput={(e) => {
                resize();
                props.onInput?.(e);
            }}
            className={clsx(
                'tasks-text-input',
                {
                    'bg-transparent': variant === 'transparent',
                    '': variant === 'default',
                },
                className
            )}
        />
    );
};

export default TextArea;
