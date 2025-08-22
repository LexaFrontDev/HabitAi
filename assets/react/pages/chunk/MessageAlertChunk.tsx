import { toast } from "react-toastify";

export const Messages = (text: string) => toast.info(text);
export const SuccessAlert = (text: string) => toast.success(text);
export const ErrorAlert = (text: string) => toast.error(text);
export const IsDoneAlert = (text: string) => toast.success(text);