import { Component } from '@angular/core';
import {MatTableDataSource, MatTableModule} from '@angular/material/table';
import {MatIconModule} from '@angular/material/icon';
import { ColumnDefinition, SharedTableComponent, TableAction, TableData } from '../../../../shared/shared-table/shared-table.component';

@Component({
  selector: 'app-withdraw-list',
  standalone: true,
  imports: [MatTableModule,MatIconModule,SharedTableComponent],
  templateUrl: './withdraw-list.component.html',
  styleUrl: './withdraw-list.component.scss'
})
export class WithdrawListComponent {
  companyData: TableData[] = [
    { id: 1, Amount: 'Test Company', AccountType: 'test-company', AccountNo: 1, AccountName: 1, Status: 1, CreatedAt: 1},
    { id: 2, Amount: 'Another Company', AccountType: 'another-company', AccountNo: 2, AccountName: 5, Status: 1, CreatedAt: 1},
    { id: 3, Amount: 'Test Company', AccountType: 'test-company', AccountNo: 1, AccountName: 1, Status: 1, CreatedAt: 1},
    { id: 4, Amount: 'Another Company', AccountType: 'another-company', AccountNo: 2, AccountName: 5, Status: 1, CreatedAt: 1},
    { id: 5, Amount: 'Test Company', AccountType: 'test-company', AccountNo: 1, AccountName: 1, Status: 1, CreatedAt: 1},
    { id: 6, Amount: 'Another Company', AccountType: 'another-company', AccountNo: 2, AccountName: 5, Status: 1, CreatedAt: 1},
    { id: 7, Amount: 'Test Company', AccountType: 'test-company', AccountNo: 1, AccountName: 1, Status: 1, CreatedAt: 1},
    { id: 8, Amount: 'Another Company', AccountType: 'another-company', AccountNo: 2, AccountName: 5, Status: 1, CreatedAt: 1},
    { id: 9, Amount: 'Test Company', AccountType: 'test-company', AccountNo: 1, AccountName: 1, Status: 1, CreatedAt: 1},
    { id: 10, Amount: 'Another Company', AccountType: 'another-company', AccountNo: 2, AccountName: 5, Status: 1, CreatedAt: 1},
    { id: 11, Amount: 'Another Company', AccountType: 'another-company', AccountNo: 2, AccountName: 5, Status: 1, CreatedAt: 1},
    // Add more data as needed
  ];

  columns: ColumnDefinition[] = [
    { key: 'Amount', header: 'Amount' },
    { key: 'AccountType', header: 'Account Type', backgroundColor: 'orange', textColor: 'black', customdiv: true },
    { key: 'AccountNo', header: 'Account No', backgroundColor: 'purple', textColor: 'black', customdiv: true },
    { key: 'AccountName', header: 'Account Name', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'Status', header: 'Status' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
    { key: 'CreatedAt', header: 'Created At' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
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
