export interface BaseProps {
    class?: string;
}

export interface VariantProps {
    variant?: 'default' | 'secondary' | 'destructive' | 'outline' | 'ghost';
    size?: 'default' | 'sm' | 'lg' | 'icon';
}

export interface FormFieldProps extends BaseProps {
    id?: string;
    name?: string;
    placeholder?: string;
    required?: boolean;
    disabled?: boolean;
}

export interface FormErrorProps {
    errors?: Record<string, string>;
    field: string;
}

export interface LoadingState {
    isLoading: boolean;
    message?: string;
}

export interface SearchProps {
    value: string;
    placeholder?: string;
    onSearch: (value: string) => void;
    onClear: () => void;
    isSearching?: boolean;
}
