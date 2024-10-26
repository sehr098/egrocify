import { AfterViewInit, Component, Input, OnInit, ViewChild } from '@angular/core';
import { MatTableDataSource, MatTableModule } from '@angular/material/table';
import { MatPaginator, MatPaginatorModule, PageEvent } from '@angular/material/paginator';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatMenuModule } from '@angular/material/menu';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-shared-table',
  standalone: true,
  imports: [
    MatMenuModule,
    MatIconModule,
    MatButtonModule,
    MatTableModule,
    CommonModule,
    MatPaginatorModule,
    MatFormFieldModule,
    MatInputModule
  ],
  templateUrl: './shared-table.component.html',
  styleUrls: ['./shared-table.component.scss']
})
export class SharedTableComponent implements AfterViewInit, OnInit {
  @Input() set data(data: TableData[]) {
    this.dataSource.data = data;
    this.length = data.length;
    this.updatePagedData();
  }
  @Input() columns: ColumnDefinition[] = [];
  @Input() actions: TableAction[] = [];

  dataSource = new MatTableDataSource<TableData>();
  pagedDataSource = new MatTableDataSource<TableData>();
  displayedColumnKeys: string[] = [];

  @ViewChild('paginator') paginator!: MatPaginator;
  @ViewChild('paginator1') paginator1!: MatPaginator;

  length = 0;
  pageSize = 10;
  pageIndex = 0;
  pageSizeOptions = [5, 10, 15, 20, 25, 50, 100];

  hidePageSize = false;
  showPageSizeOptions = true;
  showFirstLastButtons = true;
  hideFirstPaginator = true;
  constructor() { }

  actionClick(action: TableAction, element: TableData) {
    if (action.action) {
      action.action(element);
    }
  }

  applyFilter(event: Event) {
    const filterValue = (event.target as HTMLInputElement).value;
    this.dataSource.filter = filterValue.trim().toLowerCase();

    if (this.dataSource.paginator) {
      this.dataSource.paginator.firstPage();
    }
    this.updatePagedData();
  }

  handlePageEvent(e: PageEvent) {
    this.pageSize = e.pageSize;
    this.pageIndex = e.pageIndex;
    this.updatePagedData();
    this.syncPaginators();
  }

  ngOnInit() {
    this.displayedColumnKeys = this.columns.map(col => col.key);
    this.displayedColumnKeys.push('actions'); // Add the actions column key
  }

  ngAfterViewInit() {
    const paginatorRangeActions = document.querySelectorAll(".mat-mdc-paginator-range-actions");
    if (paginatorRangeActions.length > 0 && this.hideFirstPaginator) {
      paginatorRangeActions[0].classList.add('d-none');
    }
    this.dataSource.paginator = this.paginator;
    this.syncPaginators();
    this.updatePagedData();
  }

  updatePagedData() {
    const startIndex = this.pageIndex * this.pageSize;
    const endIndex = startIndex + this.pageSize;
    this.pagedDataSource.data = this.dataSource.data.slice(startIndex, endIndex);
  }

  syncPaginators() {
    if (this.paginator && this.paginator1) {
      this.paginator1.pageIndex = this.paginator.pageIndex;
      this.paginator1.pageSize = this.paginator.pageSize;
      this.paginator1.length = this.paginator.length;
    }
  }
}

export interface TableData {
  [key: string]: any;
}

export interface ColumnDefinition {
  key: string;
  header: string;
  backgroundColor?: string;
  textColor?: string;
  customdiv?: boolean;
}

export interface TableAction {
  label: string;
  action: (element: TableData) => void;
}
