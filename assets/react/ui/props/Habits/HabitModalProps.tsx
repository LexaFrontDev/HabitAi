import {HabitTemplate} from "./HabitTemplate";
import {DataType} from "./DataType";
import {EditDataType} from "./EditHabitsDataType";

export interface HabitModalProps {
    habitTemplates?: HabitTemplate[];
    onClose: () => void;
    onSave?: (data: DataType) => void;
    onEdit?: (data: EditDataType) => void;
    edit?: boolean;
    editData?: any;
}
