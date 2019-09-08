#include <stdio.h>
#include <shellapi.h>
#include <windows.h>

LRESULT CALLBACK WindowProcedure (HWND, UINT, WPARAM, LPARAM);

FILE *crypted;
FILE *hided;

HWND hwnd;
HWND hCombo;
HWND hEdit1;
HWND hEdit2;
HWND hPath1;
HWND hPath2;
HWND hRun;
HWND hKey;
HWND hHlp;

char szClassName[ ] = "WindowsApp";
char maintext[50] = "Vonsovsky crypting";
char extension[256] = "";
char cryptedfile[256] = "";
char hidedfile[256] = "";
char key[256] = "";
char znak[50];
char szSoubor[256];

#define ID_PATH1 100
#define ID_PATH2 101
#define ID_RUN	 102
#define ID_HLP	 103

void cmdLine(char *cmd) {
	char tstr[256] = "";
	char h1[256] = "";
	int j = 0;
	for (int i = 0; i < strlen(cmd); i++) {
		if (cmd[i] == 32) {
			SetWindowText(hEdit1, tstr);
			memset(tstr, 0, sizeof(tstr));
			j = i + 1;
			continue;
		}
		tstr[i-j] = cmd[i];
	}
	SendMessage(hEdit1, WM_GETTEXT, 255, (LPARAM)h1);
	if (strcmp(h1, "") != 0) SetWindowText(hEdit2, tstr);
	else SetWindowText(hEdit1, tstr);
}

int Encrypt(char *text) {
	if (strcmp(text, "") == 0) {
		text[0] = 0 + key[0] % 26;
		return 0;
	}
	for (int i = 0; i < strlen(text); i++) {
		int posun = i % strlen(key);
		text[i] += key[posun] % 26;
	}
	return 0;
}
int Decrypt(char *text) {
	if (strcmp(text, "") == 0) {
		text[0] = 0 - key[0] % 26;
		return 0;
	}
	for (int i = 0; i < strlen(text); i++) {
		int posun = i % strlen(key);
		text[i] -= key[posun] % 26;
	}
	return 0;
}

int Encode(void) {
	crypted = fopen(cryptedfile, "ab");
	hided = fopen(hidedfile, "rb");

	char ext[5] = "";
	ext[0] = hidedfile[strlen(hidedfile)-3]; ext[1] = hidedfile[strlen(hidedfile)-2]; ext[2] = hidedfile[strlen(hidedfile)-1];

	char maintexten[256];
	char exten[256];
	strcpy(maintexten, maintext);
	strcpy(exten, ext);
	Encrypt(maintexten);
	Encrypt(exten);

	fputs(maintexten, crypted);
	fputs(exten, crypted);

	while (fgets(znak, 2, hided) != NULL) {
		Encrypt(znak);
		fputs(znak, crypted);
		if (strcmp(znak, "") == 0) fputc(0, crypted);
	}
	fclose(crypted);
	fclose(hided);
	MessageBox(NULL, "Kódování probìhlo úspìšnì", "Výsledek", 0 + MB_OK + MB_ICONASTERISK + MB_TASKMODAL);
	return 0;
}

void Napln(char *hlp) {
	for (int i = 0; i < strlen(maintext); i++) {
		hlp[i] = 122;
	}
}

void posun(char *obj) {
	for (int i = 1; i < strlen(obj); i++) {
		obj[i-1] = obj[i];
	}
}

int Decode(void) {
	char hlp[50] = "";
	Napln(hlp);
	int pocitadlo = 0;
	FILE *tmp = NULL;
	char string[45] = "";
	int zapis = 0;
	char dec[50] = "";
	char tempfile[256] = "";
	crypted = fopen(cryptedfile, "rb");
	while (fgets(znak, 2, crypted) != NULL) {
		if (zapis == 0) {
			posun(hlp);
			hlp[strlen(maintext)-1] = znak[0];
			strcpy(dec, hlp);
			Decrypt(dec);
		}
		if (zapis == 2) {
			Decrypt(znak);
			fputs(znak, tmp);
			if (strcmp(znak, "") == 0) fputc(0, tmp);
		}
		if ((strcmp(dec, maintext) == 0) && (zapis == 0)) {
			zapis = 2;
			fgets(extension, 4, crypted);
			Decrypt(extension);
			sprintf(tempfile, "tmp.%s", extension);
			tmp = fopen(tempfile, "wb");
		}
	}
	if (strcmp(tempfile, "") == 0) {
		MessageBox(NULL, "Špatný klíè", "Chyba", 0 + MB_OK + MB_TASKMODAL + MB_ICONERROR);
		return 0;
	}
	ShellExecute(NULL, "open", tempfile, NULL, NULL, SW_SHOWNORMAL);
	fclose(tmp);
	fclose(crypted);
	MessageBox(NULL, "Po shlédnutí souboru potvrïte enterem,\nodkládací soubor se následnì smaže.\nPokud chcete soubor zachovat zkopírujte odkládací soubor než stisknete tlaèítko", "Info", 0 + MB_OK + MB_ICONASTERISK + MB_TASKMODAL);
	DeleteFile(tempfile);

	return 0;
}

int goandreturn(void) {
	char ths[50] = "";
	char ds[50] = "";
	char sznak[50] = "";
	Napln(ths);
	for (int i = 0; i < strlen(maintext); i++) {
		fgets(sznak, 2, crypted);
		for (int j = 1; j < strlen(ths); j++) {
			ths[j-1] = ths[j];
		}
		ths[strlen(maintext)-1] = sznak[0];
		strcpy(ds, ths);
		Decrypt(ds);
		if (strcmp(ds, maintext) == 0) return TRUE;
	}
	fseek(crypted, strlen(maintext) * -1, SEEK_CUR);
	return FALSE;
}


int Delete(void) {
	char hlp[50] = "";
	Napln(hlp);
	int pocitadlo = 0;
	FILE *tmp = fopen("temp.tmp", "wb");
	char string[45] = "";
	int zapis = 0;
	char dec[50] = "";
	char tempfile[256] = "";
	crypted = fopen(cryptedfile, "rb");
	while (fgets(znak, 2, crypted) != NULL) {
		if (zapis == 0) {
			fputs(znak, tmp);
			if (strcmp(znak, "") == 0) fputc(0, tmp);
			posun(hlp);
			hlp[strlen(maintext)-1] = znak[0];
			strcpy(dec, hlp);
			Decrypt(dec);
			pocitadlo++;
		}
		if ((strcmp(dec, maintext) == 0) && (zapis == 0)) {
			fclose(tmp);
			fclose(crypted);
			crypted = fopen(cryptedfile, "wb");
			tmp = fopen("temp.tmp", "rb");
			for (int i = 0; i < pocitadlo - strlen(maintext); i++) {
				fgets(znak, 2, tmp);
				fputs(znak, crypted);
				if (strcmp(znak, "") == 0) fputc(0, crypted);
			}
			fclose(tmp);
			fclose(crypted);
			DeleteFile("temp.tmp");
			return 0;
		}
	}
	if (strcmp(tempfile, "") == 0) {
		MessageBox(NULL, "Špatný klíè", "Chyba", 0 + MB_OK + MB_TASKMODAL + MB_ICONERROR);
		return 0;
	}
	fclose(tmp);
	fclose(crypted);

	return 0;
}
//windows interface
int WINAPI WinMain(HINSTANCE hThisInstance, HINSTANCE hPrevInstance, LPSTR lpszArgument, int nFunsterStil) {
    MSG messages;
    WNDCLASSEX wincl;

    wincl.hInstance = hThisInstance;
    wincl.lpszClassName = szClassName;
    wincl.lpfnWndProc = WindowProcedure;
    wincl.style = CS_DBLCLKS;
    wincl.cbSize = sizeof (WNDCLASSEX);

    wincl.hIcon = LoadIcon (NULL, IDI_APPLICATION);
    wincl.hIconSm = LoadIcon (NULL, IDI_APPLICATION);
    wincl.hCursor = LoadCursor (NULL, IDC_ARROW);
    wincl.lpszMenuName = NULL;
    wincl.cbClsExtra = 0;
    wincl.cbWndExtra = 0;
    wincl.hbrBackground = (HBRUSH) COLOR_BACKGROUND;

    if (!RegisterClassEx (&wincl))
        return 0;

    hwnd = CreateWindowEx (
           0,
           szClassName,
           "Steg",
           WS_OVERLAPPEDWINDOW,
           CW_USEDEFAULT,
           CW_USEDEFAULT,
           310,
           200,
           HWND_DESKTOP,
           NULL,
           hThisInstance,
           NULL
           );

	hCombo = CreateWindowEx(0, "combobox", "", WS_CHILD | WS_VISIBLE | CBS_DROPDOWNLIST, 5, 5, 250, 150, hwnd, NULL, hThisInstance, NULL);
	hEdit1 = CreateWindowEx(0, "edit", "", WS_CHILD | WS_VISIBLE | WS_BORDER | ES_AUTOHSCROLL, 5, 50, 247, 20, hwnd, NULL, hThisInstance, NULL);
	hEdit2 = CreateWindowEx(0, "edit", "", WS_CHILD | WS_VISIBLE | WS_BORDER, 5, 95, 247, 20, hwnd, NULL, hThisInstance, NULL);
	hRun = CreateWindowEx(0, "button", ">", WS_CHILD | WS_VISIBLE | WS_BORDER, 260, 5, 30, 23, hwnd, (HMENU)ID_RUN, hThisInstance, NULL);
	hPath1 = CreateWindowEx(0, "button", "...", WS_CHILD | WS_VISIBLE | WS_BORDER, 260, 50, 30, 20, hwnd, (HMENU)ID_PATH1, hThisInstance, NULL);
	hPath2 = CreateWindowEx(0, "button", "...", WS_CHILD | WS_VISIBLE | WS_BORDER, 260, 95, 30, 20, hwnd, (HMENU)ID_PATH2, hThisInstance, NULL);
	hHlp = CreateWindowEx(0, "button", "?", WS_CHILD | WS_VISIBLE | WS_BORDER, 260, 140, 30, 20, hwnd, (HMENU)ID_HLP, hThisInstance, NULL);
	hKey = CreateWindowEx(0, "edit", "klíè?", WS_BORDER | WS_CHILD | WS_VISIBLE | ES_AUTOHSCROLL, 5, 140, 247, 20, hwnd, NULL, hThisInstance, NULL);

    ShowWindow (hwnd, nFunsterStil);
	SendMessage(hCombo, CB_ADDSTRING, 0, (LPARAM)"Zašifruj soubor do jiného");
	SendMessage(hCombo, CB_ADDSTRING, 0, (LPARAM)"Dešifruj soubor z jiného");
	SendMessage(hCombo, CB_ADDSTRING, 0, (LPARAM)"Vyèisti již jednou použitý soubor");
	SendMessage(hCombo, CB_SETCURSEL, 0, 0);

	if (strcmp(lpszArgument, "") != 0) cmdLine(lpszArgument);

    while (GetMessage (&messages, NULL, 0, 0))
    {
        TranslateMessage(&messages);
        DispatchMessage(&messages);
    }
    return messages.wParam;
}

int Load(void) {
	lstrcpy(szSoubor, "");
	OPENFILENAME ofn;
	ZeroMemory(&ofn, sizeof(OPENFILENAME));
	ofn.lStructSize = sizeof(OPENFILENAME);
	ofn.hwndOwner = hwnd;
	ofn.lpstrFile = szSoubor;
	ofn.nMaxFile = sizeof(szSoubor);
	ofn.lpstrFilter = TEXT("Všechny soubory\0*.*\0");
	ofn.nFilterIndex = 1;
	ofn.lpstrFileTitle = NULL;
	ofn.nMaxFileTitle = 0;
	ofn.lpstrInitialDir = NULL;
	ofn.Flags = OFN_PATHMUSTEXIST | OFN_FILEMUSTEXIST | OFN_EXTENSIONDIFFERENT;
	if ( !GetOpenFileName(&ofn) )
		return FALSE;;

	return TRUE;
}


LRESULT CALLBACK WindowProcedure (HWND hwnd, UINT message, WPARAM wParam, LPARAM lParam)
{
    switch (message)
    {
        case WM_COMMAND:
			switch (LOWORD(wParam)) {
				case ID_HLP:
					char help[512] = "";
					sprintf (help, "Program slouží k ukrytí jednoho souboru do druhého, vhodné je napø. šifrovat do bmp nebo jpg obrázkù.\nPrvní cesta souboru v pøípadì zašifrování znamená, kam se bude ten druhý schovávat.\nV pøípadì dešifrování se soubor dešifruje a zobrazí se soubor v nìm schovaný.\nV tøetím pøípadì se soubor vyèistí, aby šel znovu použít ke skrytí jiného souboru.\nV druhém a tøetím pøípadì je druhá cesta souboru nepovinná (vùbec se nepoužije).");
					MessageBox(NULL, help, "Steg - Jakub Vonšovský", 0 + MB_OK + MB_ICONASTERISK);
					break;
				case ID_PATH1:
					if (Load()) {
						SetWindowText(hEdit1, szSoubor);
					}
					break;
				case ID_PATH2:
					if (Load()) {
						SetWindowText(hEdit2, szSoubor);
					}
					break;
				case ID_RUN:
					SendMessage(hEdit1, WM_GETTEXT, 255, (LPARAM)cryptedfile);
					SendMessage(hEdit2, WM_GETTEXT, 255, (LPARAM)hidedfile);
					SendMessage(hKey, WM_GETTEXT, 255, (LPARAM)key);
					if (strcmp(cryptedfile, "") == 0) {
						MessageBox(NULL, "Není vybrán hlavní soubor!", "Chyba", 0 + MB_OK + MB_TASKMODAL + MB_ICONERROR);
						break;
					}
					int cur = SendMessage(hCombo, CB_GETCURSEL, 0, 0);
					switch (cur) {
						case 0:
							if (strcmp(hidedfile, "") == 0) {
								MessageBox(NULL, "Není vybrán druhý soubor!", "Chyba", 0 + MB_OK + MB_TASKMODAL + MB_ICONERROR);
								break;
							}
							Encode();
							break;
						case 1:
							if (MessageBox(NULL, "Proces dekodování je složitý a zabere pøibližnì 6sec / 1MB (1200 Mhz)", "Opravdu chcete pokraèovat?", 0 + MB_OKCANCEL + MB_TASKMODAL + MB_ICONASTERISK) == IDOK)
								Decode();
							break;
						case 2:
							Delete();
							break;
					}
					break;
			}
			break;

		case WM_DESTROY:
            PostQuitMessage (0);
            break;
        default:
            return DefWindowProc (hwnd, message, wParam, lParam);
    }

    return 0;
}

