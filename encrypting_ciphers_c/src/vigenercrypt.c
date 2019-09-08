#include <stdio.h>
#include <string.h>

void Encrypt(void) {
	char text[255];
	printf("input text\n");
	scanf("%s", text);
	printf("key\n");
	char key[255];
	scanf("%s", &key);
	strupr(text);
	for (int i = 0; i < strlen(text); i++) {
		int posun = i % strlen(key);
		text[i] += key[posun] % 26;
		while (text[i] > 90) text[i] -= 26;
	}
	strlwr(text);
	printf("%s", text);
}
void Decrypt(void) {
	char text[255];
	printf("input coded text\n");
	scanf("%s", text);
	printf("key\n");
	char key[255];
	scanf("%s", &key);
	strupr(text);
	for (int i = 0; i < strlen(text); i++) {
		int posun = i % strlen(key);
		text[i] -= key[posun] % 26;
		while (text[i] < 65) text[i] += 26;
	}
	strlwr(text);
	printf("%s", text);
}

int main(void) {
	char eord[3];
	printf("Encrypt or Decrypt?");
	scanf("%s", eord);
	if ((strcmp("e", eord) == 0) || (strcmp("E", eord) == 0)) {
		Encrypt();
		return 0;
	}
	if ((strcmp("d", eord) == 0) || (strcmp("D", eord) == 0)) {
		Decrypt();
		return 0;
	}
	printf("Only letter e or d are allowed");
	return 0;
}

