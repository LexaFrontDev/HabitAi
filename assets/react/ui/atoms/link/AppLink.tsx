import React from 'react';
import { Link, LinkProps } from 'react-router-dom';
import clsx from 'clsx';

interface AppLinkProps extends LinkProps {
    className?: string;
    variant?: 'default' | 'nav' | 'footer';
}

export const AppLink: React.FC<AppLinkProps> = ({
                                                    to,
                                                    children,
                                                    className,
                                                    variant = 'default',
                                                    ...props
                                                }) => {
    const base = 'text-decoration-none';
    const variants = {
        default: 'me-2 text-primary',
        nav: 'me-2',
        footer: 'me-2 text-muted small',
    };

    return (
        <Link
            to={to}
            className={clsx(base, variants[variant], className)}
            {...props}
        >
            {children}
        </Link>
    );
};
