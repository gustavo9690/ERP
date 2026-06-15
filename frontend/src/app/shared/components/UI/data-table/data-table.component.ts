import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormsModule } from '@angular/forms';

export interface DataTableColumn {
  key: string;
  label: string;
  type?: 'text' | 'number' | 'status';
  sortable?: boolean;
  width?: string;
}

export interface DataTableAction {
  key: string;
  label: string;
  icon?: string;
  type?: 'primary' | 'default' | 'danger' | 'success' | 'warning';
  visible?: (row: any) => boolean;
  disabled?: (row: any) => boolean;
}

@Component({
  selector: 'app-data-table',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule
  ],
  templateUrl: './data-table.component.html',
  styleUrls: ['./data-table.component.scss']
})
export class DataTableComponent {

  @Input() title: string = '';
  @Input() subtitle: string = '';

  @Input() columns: DataTableColumn[] = [];
  @Input() data: any[] = [];

  @Input() actions: DataTableAction[] = [];

  @Input() searchable: boolean = true;
  @Input() filterable: boolean = true;
  @Input() exportable: boolean = true;
  @Input() paginated: boolean = true;
  @Input() selectable: boolean = false;
  @Input() showActions: boolean = true;

  @Input() loading: boolean = false;

  @Input() pageSizeOptions: number[] = [5, 10, 20, 50];

  @Output() create = new EventEmitter<void>();

  @Output() actionClick = new EventEmitter<{
    action: DataTableAction;
    row: any;
  }>();

  @Output() export = new EventEmitter<void>();

  searchText: string = '';

  currentPage: number = 1;
  pageSize: number = 5;

  sortColumn: string = '';
  sortDirection: 'asc' | 'desc' = 'asc';

  selectedRows = new Set<any>();

  get filteredData(): any[] {

    const value = this.searchText.trim().toLowerCase();

    if (!value) {
      return [...this.data];
    }

    return this.data.filter(row =>
      this.columns.some(column =>
        String(row[column.key] ?? '')
          .toLowerCase()
          .includes(value)
      )
    );
  }

  get sortedData(): any[] {

    const rows = [...this.filteredData];

    if (!this.sortColumn) {
      return rows;
    }

    return rows.sort((a, b) => {

      const valueA = a[this.sortColumn];
      const valueB = b[this.sortColumn];

      if (valueA == null) return 1;
      if (valueB == null) return -1;

      const result = String(valueA).localeCompare(
        String(valueB),
        undefined,
        {
          numeric: true,
          sensitivity: 'base'
        }
      );

      return this.sortDirection === 'asc'
        ? result
        : -result;

    });
  }

  get pagedData(): any[] {

    if (!this.paginated) {
      return this.sortedData;
    }

    const start = (this.currentPage - 1) * this.pageSize;

    return this.sortedData.slice(
      start,
      start + this.pageSize
    );
  }

  get totalPages(): number {

    return Math.max(
      1,
      Math.ceil(this.sortedData.length / this.pageSize)
    );
  }

  get startItem(): number {

    if (this.sortedData.length === 0) {
      return 0;
    }

    return ((this.currentPage - 1) * this.pageSize) + 1;
  }

  get endItem(): number {

    return Math.min(
      this.currentPage * this.pageSize,
      this.sortedData.length
    );
  }

  get allVisibleSelected(): boolean {

    return this.pagedData.length > 0 &&
      this.pagedData.every(row =>
        this.selectedRows.has(row)
      );
  }

  onSearchChange(): void {
    this.currentPage = 1;
  }

  sortBy(column: DataTableColumn): void {

    if (column.sortable === false) {
      return;
    }

    if (this.sortColumn === column.key) {

      this.sortDirection =
        this.sortDirection === 'asc'
          ? 'desc'
          : 'asc';

    } else {

      this.sortColumn = column.key;
      this.sortDirection = 'asc';

    }
  }

  changePageSize(): void {
    this.currentPage = 1;
  }

  goToPage(page: number): void {

    if (page < 1 || page > this.totalPages) {
      return;
    }

    this.currentPage = page;
  }

  toggleRow(row: any): void {

    if (this.selectedRows.has(row)) {
      this.selectedRows.delete(row);
    } else {
      this.selectedRows.add(row);
    }
  }

  toggleAllVisible(): void {

    if (this.allVisibleSelected) {

      this.pagedData.forEach(row =>
        this.selectedRows.delete(row)
      );

    } else {

      this.pagedData.forEach(row =>
        this.selectedRows.add(row)
      );

    }
  }

  isSelected(row: any): boolean {
    return this.selectedRows.has(row);
  }

  clearSearch(): void {

    this.searchText = '';
    this.currentPage = 1;
  }

  onAction(action: DataTableAction, row: any): void {

    if (this.isActionDisabled(action, row)) {
      return;
    }

    this.actionClick.emit({
      action,
      row
    });
  }

  isActionVisible(
    action: DataTableAction,
    row: any
  ): boolean {

    return action.visible
      ? action.visible(row)
      : true;
  }

  isActionDisabled(
    action: DataTableAction,
    row: any
  ): boolean {

    return action.disabled
      ? action.disabled(row)
      : false;
  }

  trackByRow(index: number, row: any): any {
    return row.id || row.idModulo || index;
  }

}