import { Component } from '@angular/core';
import {MatTableDataSource, MatTableModule} from '@angular/material/table';
import {MatIconModule} from '@angular/material/icon';
import { ColumnDefinition, SharedTableComponent, TableAction, TableData } from '../../../shared/shared-table/shared-table.component';

@Component({
  selector: 'app-package-list',
  standalone: true,
  imports: [MatTableModule,MatIconModule,SharedTableComponent],
  templateUrl: './package-list.component.html',
  styleUrl: './package-list.component.scss'
})
export class PackageListComponent {
  companyData: TableData[] = [
    { id: 1, Name: 'Test Company', Description: 'test-company', Price: 1, ReferralAward: 1, WithdrawLimit: 1},
    { id: 2, Name: 'Another Company', Description: 'another-company', Price: 2, ReferralAward: 5, WithdrawLimit: 1},
    { id: 3, Name: 'Test Company', Description: 'test-company', Price: 1, ReferralAward: 1, WithdrawLimit: 1},
    { id: 4, Name: 'Another Company', Description: 'another-company', Price: 2, ReferralAward: 5, WithdrawLimit: 1},
    { id: 5, Name: 'Test Company', Description: 'test-company', Price: 1, ReferralAward: 1, WithdrawLimit: 1},
    { id: 6, Name: 'Another Company', Description: 'another-company', Price: 2, ReferralAward: 5, WithdrawLimit: 1},
    { id: 7, Name: 'Test Company', Description: 'test-company', Price: 1, ReferralAward: 1, WithdrawLimit: 1},
    { id: 8, Name: 'Another Company', Description: 'another-company', Price: 2, ReferralAward: 5, WithdrawLimit: 1},
    { id: 9, Name: 'Test Company', Description: 'test-company', Price: 1, ReferralAward: 1, WithdrawLimit: 1},
    { id: 10, Name: 'Another Company', Description: 'another-company', Price: 2, ReferralAward: 5, WithdrawLimit: 1},
    { id: 11, Name: 'Another Company', Description: 'another-company', Price: 2, ReferralAward: 5, WithdrawLimit: 1},
    // Add more data as needed
  ];

  columns: ColumnDefinition[] = [
    { key: 'Name', header: 'Name' },
    { key: 'Description', header: 'Description', backgroundColor: 'orange', textColor: 'black', customdiv: true },
    { key: 'Price', header: 'Price', backgroundColor: 'purple', textColor: 'black', customdiv: true },
    { key: 'ReferralAward', header: 'Referral Award', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'WithdrawLimit', header: 'Withdraw Limit' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
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
