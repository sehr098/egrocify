import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class HttpService {
  constructor(private http: HttpClient) {}

  getData(url: string): Observable<any> {
    return this.http.get<any>(url).pipe(
      catchError((error: HttpErrorResponse) => this.handleApiError(error))
    );
  }

  private handleApiError(error: HttpErrorResponse): Observable<never> {
    console.error('API Error:', error);
    alert(`Error occurred: ${error.message}`); // Added alert for quick debugging
    return throwError(error);
  }

  getComments(postId: number): Observable<any> {
    return this.http.get<any>(`https://dummyjson.com/products/${postId}`).pipe(
      catchError((error: HttpErrorResponse) => this.handleApiError(error))
    );
  }
}
