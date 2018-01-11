package ep.rest;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import java.io.IOException;

import okhttp3.Headers;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class IzdelekFormActivity extends AppCompatActivity
        implements View.OnClickListener, Callback<Void> {
    private static final String TAG = IzdelekFormActivity.class.getCanonicalName();

    private EditText author, title, price, description, year;
    private Button button;

    private Izdelek izdelek;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_book_form);

        author = (EditText) findViewById(R.id.etAuthor);
        title = (EditText) findViewById(R.id.etTitle);
        price = (EditText) findViewById(R.id.etPrice);
        year = (EditText) findViewById(R.id.etYear);
        description = (EditText) findViewById(R.id.etDescription);
        button = (Button) findViewById(R.id.button);
        button.setOnClickListener(this);

        final Intent intent = getIntent();
        izdelek = (Izdelek) intent.getSerializableExtra("ep.rest.izdelek");
        if (izdelek != null) {
            author.setText(izdelek.author);
            title.setText(izdelek.ime);
            price.setText(String.valueOf(izdelek.cena));
            year.setText(String.valueOf(izdelek.year));
            description.setText(izdelek.opis);
        }
    }

    @Override
    public void onClick(View view) {
        final String bookAuthor = author.getText().toString().trim();
        final String bookTitle = title.getText().toString().trim();
        final String bookDescription = description.getText().toString().trim();
        final double bookPrice = Double.parseDouble(price.getText().toString().trim());
        final int bookYear = Integer.parseInt(year.getText().toString().trim());

        if (izdelek == null) { // dodajanje
            TrgovinaService.getInstance().insert(bookAuthor, bookTitle, bookPrice,
                    bookYear, bookDescription).enqueue(this);
        } else { // urejanje
            TrgovinaService.getInstance().update(izdelek.id, bookAuthor, bookTitle, bookPrice,
                    bookYear, bookDescription).enqueue(this);
        }
    }

    @Override
    public void onResponse(Call<Void> call, Response<Void> response) {
        final Headers headers = response.headers();

        if (response.isSuccessful()) {
            final int id;
            if (izdelek == null) {
                Log.i(TAG, "Insertion completed.");
                // Preberemo Location iz zaglavja
                final String[] parts = headers.get("Location").split("/");
                id = Integer.parseInt(parts[parts.length - 1]);
            } else {
                Log.i(TAG, "Editing saved.");
                id = izdelek.id;
            }
            final Intent intent = new Intent(this, IzdelekDetailActivity.class);
            intent.putExtra("ep.rest.id", id);
            startActivity(intent);
        } else {
            String errorMessage;
            try {
                errorMessage = "An error occurred: " + response.errorBody().string();
            } catch (IOException e) {
                errorMessage = "An error occurred: error while decoding the error message.";
            }
            Log.e(TAG, errorMessage);
        }
    }

    @Override
    public void onFailure(Call<Void> call, Throwable t) {
        Log.w(TAG, "Error: " + t.getMessage(), t);
    }
}
