import face_recognition
import sys
import json

def main():
    try:
        if len(sys.argv) != 3:
            print(json.dumps({"status": "error", "message": "Invalid number of arguments"}))
            return

        id_card_image_path = sys.argv[1]
        face_image_path = sys.argv[2]

        # Load images
        id_card_image = face_recognition.load_image_file(id_card_image_path)
        face_image = face_recognition.load_image_file(face_image_path)

        # Encode faces
        id_card_encodings = face_recognition.face_encodings(id_card_image)
        face_encodings = face_recognition.face_encodings(face_image)

        if not id_card_encodings or not face_encodings:
            print(json.dumps({"status": "error", "message": "No faces found in one of the images"}))
            return

        id_card_encoding = id_card_encodings[0]
        face_encoding = face_encodings[0]

        # Compare faces
        results = face_recognition.compare_faces([id_card_encoding], face_encoding)

        if results[0]:
            print(json.dumps({"status": "verified", "message": "Face verification successful"}))
        else:
            print(json.dumps({"status": "failed", "message": "Face verification failed"}))

    except Exception as e:
        print(json.dumps({"status": "error", "message": str(e)}))

if __name__ == "__main__":
    main()
