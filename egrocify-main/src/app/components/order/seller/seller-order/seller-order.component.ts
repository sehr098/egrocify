import { Component } from '@angular/core';
import {MatTableDataSource, MatTableModule} from '@angular/material/table';
import {MatIconModule} from '@angular/material/icon';
import { ColumnDefinition, SharedTableComponent, TableAction, TableData } from '../../../../shared/shared-table/shared-table.component';

@Component({
  selector: 'app-seller-order',
  standalone: true,
  imports: [MatTableModule,MatIconModule,SharedTableComponent],
  templateUrl: './seller-order.component.html',
  styleUrl: './seller-order.component.scss'
})
export class SellerOrderComponent {
  companyData: TableData[] = [
    { id: 1, ReferenceNo: 'Test Company', GrandTotal: 'test-company', ProductCount: 1, CreatedAt: 1, Status: 1},
    { id: 2, ReferenceNo: 'Another Company', GrandTotal: 'another-company', ProductCount: 2, CreatedAt: 5, Status: 1},
    { id: 3, ReferenceNo: 'Test Company', GrandTotal: 'test-company', ProductCount: 1, CreatedAt: 1, Status: 1},
    { id: 4, ReferenceNo: 'Another Company', GrandTotal: 'another-company', ProductCount: 2, CreatedAt: 5, Status: 1},
    { id: 5, ReferenceNo: 'Test Company', GrandTotal: 'test-company', ProductCount: 1, CreatedAt: 1, Status: 1},
    { id: 6, ReferenceNo: 'Another Company', GrandTotal: 'another-company', ProductCount: 2, CreatedAt: 5, Status: 1},
    { id: 7, ReferenceNo: 'Test Company', GrandTotal: 'test-company', ProductCount: 1, CreatedAt: 1, Status: 1},
    { id: 8, ReferenceNo: 'Another Company', GrandTotal: 'another-company', ProductCount: 2, CreatedAt: 5, Status: 1},
    { id: 9, ReferenceNo: 'Test Company', GrandTotal: 'test-company', ProductCount: 1, CreatedAt: 1, Status: 1},
    { id: 10, ReferenceNo: 'Another Company', GrandTotal: 'another-company', ProductCount: 2, CreatedAt: 5, Status: 1},
    { id: 11, ReferenceNo: 'Another Company', GrandTotal: 'another-company', ProductCount: 2, CreatedAt: 5, Status: 1},
    // Add more data as needed
  ];

  columns: ColumnDefinition[] = [
    { key: 'ReferenceNo', header: 'Reference No' },
    { key: 'GrandTotal', header: 'Grand Total', backgroundColor: 'orange', textColor: 'black', customdiv: true },
    { key: 'ProductCount', header: 'Product Count', backgroundColor: 'purple', textColor: 'black', customdiv: true },
    { key: 'CreatedAt', header: 'Created At', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'Status', header: 'Status' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
  ];

  actions: TableAction[] = [
    { label: 'Action 1', action: this.action1 },
    { label: 'Action 2', action: this.action2 },
    { label: 'Action 3', action: this.action3 },
    { label: 'Action 4', action: this.action4 },
  ];

  action1(element: TableData) {
    console.log('Action 1 clicked', element);
  }

  action2(element: TableData) {
    console.log('Action 2 clicked', element);
  }

  action3(element: TableData) {
    console.log('Action 3 clicked', element);
  }

  action4(element: TableData) {
    console.log('Action 4 clicked', element);
  }
}
